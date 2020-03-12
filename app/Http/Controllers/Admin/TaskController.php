<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\EmployeeTask;
use App\Models\Employee;
use App\Models\Registration;
Use Mail;
use Sentinel;

class TaskController extends Controller
{
    /**
         *
         * Set middleware to quard controller.
         * @return void
     */
    public function __construct()
    {
        $this->middleware('sentinel.auth');
    }

  
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('start_date','DESC')->get();
      
        return view('admin.tasks.index', ['tasks'=>$tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->where('odjava', null)->get();

        return view('admin.tasks.create', ['employees'=>$employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Sentinel::getUser();
        $employee = Employee::where('last_name', $user->last_name)->where('first_name',$user->first_name)->first();
        if(! $request['to_employee_id']) {
            $message = session()->flash('error', 'Nemoguće spremiti zadatak bez upisanih djelatnika!');
            
            return redirect()->back()->withFlashMessage($message);
        } 
        $employee_id = implode(",",$request['to_employee_id']);

          /*  
        if($request['interval'] != null) {
            $interval = $request['interval'] . '-' .$request['period'] ;
        } else {
            $interval = $request['send_interval'];
        } */
        
        $data = array(
            'task'  	    => $request['task'],
            'employee_id'  	=> $employee->id,
            'to_employee_id'=> $employee_id,
            'start_date'    => $request['start_date'],
            'interval'      => $request['interval'],
            'active'        => $request['active']
		);
        
        if ($request['end_date'] != '') {
            $data += ['end_date'    => $request['end_date']];
        }

		$task = new Task();
        $task->saveTask($data);        
        
       // $link = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/employee_tasks';

        if( $task->start_date == date('Y-m-d') ) {
            foreach ($request['to_employee_id'] as $key => $employee_id) {
                if($key == 0) {
                    $data_task = array(
                        'task_id'  	    => $task->id,
                        'employee_id'  	=> $employee_id,
                        'comment'  	=> $request['comment']            
                    );                
            
                    $employeeTask = new EmployeeTask();
                    $employeeTask->saveEmployeeTask($data_task);

                    $email = Employee::where('id',$employee_id )->first()->email;

                    if($email != null && $email != '') {
                        try {
                            Mail::queue('email.task_form', ['employeeTask' => $employeeTask ], function ($mail) use ($email) {
                                $mail->to($email)
                                    ->from('info@duplico.hr', 'Duplico')
                                    ->subject('Novi zadatak');
                            });
                        } catch (\Throwable $th) {
                        // dd($th);
                            $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
                
                            return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                        }
                    } else {
                        $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
                
                        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                    }
                }  
            }            
        } else {
            foreach ($request['to_employee_id'] as $employee_id) {
                $email = Employee::where('id',$employee_id )->first()->email;
                if($email != null && $email != '') {
                    try {
                        Mail::queue('email.task_info', ['task' => $task ], function ($mail) use ($task, $email) {
                            $mail->to($email)
                                ->from('info@duplico.hr', 'Duplico')
                                ->subject('Novi zadatak');
                        });
                    } catch (\Throwable $th) {
                        dd($th );
                        $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
            
                        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                    }
                } else {
                    $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
            
                    return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                }
            }           
        }     
        
		$message = session()->flash('success', 'Uspješno je spremljen novi zadatak');
		
        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        $employee_tasks = EmployeeTask::where('task_id', $id)->get();

        return view('admin.tasks.show', ['task'=>$task, 'employee_tasks'=> $employee_tasks]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find( $id );
        
        $employees = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->where('odjava', null)->get();

        return view('admin.tasks.edit', ['employees'=>$employees, 'task'=>$task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(! $request['to_employee_id']) {
            $message = session()->flash('error', 'Nemoguće spremiti zadatak bez upisanih djelatnika!');
            
            return redirect()->back()->withFlashMessage($message);
        } 
        $task = Task::find( $id );
        
        $user = Sentinel::getUser();
        $employee = Employee::where('last_name', $user->last_name)->where('first_name',$user->first_name)->first();
        $employee_id = implode(",",$request['to_employee_id']);

       /*  
        if($request['interval'] != null) {
            $interval = $request['interval'] . '-' .$request['period'] ;
        } else {
            $interval = $request['send_interval'];
        } */

        $data = array(
            'task'  	    => $request['task'],
            'employee_id'  	=> $employee->id,
            'to_employee_id'=> $employee_id,
            'start_date'    => $request['start_date'],
            'interval'      => $request['interval'],
            'active'        => $request['active']
		);
        
        if ($request['end_date'] != '') {
            $data += ['end_date'    => $request['end_date']];
        }

        $task->updateTask($data); 

        if( $task->start_date == date('Y-m-d') ) {
            foreach ($request['to_employee_id'] as $key => $employee_id) {
                if($key == 0) {
                    $data_task = array(
                        'task_id'  	    => $task->id,
                        'employee_id'  	=> $employee_id,
                        'comment'  	=> $request['comment']            
                    );                
            
                    $employeeTask = new EmployeeTask();
                    $employeeTask->saveEmployeeTask($data_task);

                    $email = Employee::where('id',$employee_id )->first()->email;

                    if($email != null && $email != '') {
                        try {
                            Mail::queue('email.task_form', ['employeeTask' => $employeeTask ], function ($mail) use ($email) {
                                $mail->to($email)
                                    ->from('info@duplico.hr', 'Duplico')
                                    ->subject('Ispravak zadatka');
                            });
                        } catch (\Throwable $th) {
                        // dd($th);
                            $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
                
                            return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                        }
                    } else {
                        $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
                
                        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                    }
                }  
            }            
        } else {
            foreach ($request['to_employee_id'] as $employee_id) {
                $email = Employee::where('id',$employee_id )->first()->email;
                if($email != null && $email != '') {
                    try {
                        Mail::queue('email.task_info', ['task' => $task ], function ($mail) use ($task, $email) {
                            $mail->to($email)
                                ->from('info@duplico.hr', 'Duplico')
                                ->subject('Ispravak zadatka');
                        });
                    } catch (\Throwable $th) {
                        $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
            
                        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                    }
                } else {
                    $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
            
                    return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                }
            }           
        } 

		$message = session()->flash('success', 'Uspješno je ispravljen zadatak');
		
		return redirect()->route('admin.tasks.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find( $id );
        $task->delete();

        $message = session()->flash('success', 'Zadatak je obrisan.');
		
		return redirect()->route('admin.tasks.index')->withFlashMessage($message);
    }
}
