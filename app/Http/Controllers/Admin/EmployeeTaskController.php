<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\EmployeeTask;
use App\Models\Employee;
use Sentinel;
use Mail;

class EmployeeTaskController extends Controller
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
        $user = Sentinel::getUser();
        $employee = Employee::where('last_name', $user->last_name)->where('first_name',$user->first_name)->first();

        $employee_tasks = EmployeeTask::where('employee_id', $employee->id)->get();
      
        return view('admin.employee_tasks.index', ['employee_tasks'=>$employee_tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee_task = EmployeeTask::find($id);
        
        return view('admin.employee_tasks.edit', ['employee_task'=>$employee_task]);
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
        $employee_task = EmployeeTask::find($id);
       
        if(isset($request['mail_confirme'])) {
            $status = 1;
            $comment =  $request['comment'];
        } else {
            if( $employee_task->status  == null ) {
                $status = 1;
            } else {
                $status = null;
                $comment =  'Potvrda poništena! |' . $employee_task->comment;
            } 
        }        
        
        $data = array(
            'status'  	    => $status,
            'comment'  	    => $comment
        );
   
		$employee_task->updateEmployeeTask($data);
         
        $email_1 = $employee_task->task->employee->email;  // djelatnik koji je zadao zadatak
        $email_2 = $employee_task->employee->email;  // djelatnik koji je izvršio zadatak
        
        if( $employee_task->status == 1) {
            $title = 'Potvrda '.' izvršenja '.' zadatka';
            $status = 'potvrdio';
            $status2 = 'Potvrdio si';
        } else {
            $title = 'Poništenje '.' potvrde '.' izvršenja '.' zadatka';
            $status = 'poništio potvrdu za';
            $status2 = 'Poništio si potvrdu za';
        }
       
        Mail::queue('email.task_confirme', ['employee_task' => $employee_task, 'status' => $status], function ($mail) use ($employee_task, $email_1, $title) {
            $mail->to($email_1)
                ->from('info@duplico.hr', 'Duplico')
                ->subject($title);
        });
        Mail::queue('email.task_confirme2', ['employee_task' => $employee_task, 'status' => $status2], function ($mail) use ($employee_task, $email_2, $title) {
            $mail->to($email_2)
                ->from('info@duplico.hr', 'Duplico')
                ->subject($title);
        });

		$message = session()->flash('success', 'Zadatak je riješen');
		
		return redirect()->route('admin.employee_tasks.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function confirmTask ( $request) 
    {
      
    }
}
