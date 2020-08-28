<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instruction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Employee_department;
use App\Models\Registration;
use App\Http\Requests\InstructionRequest;
use Sentinel;
use Mail;

class InstructionController extends Controller
{
    /**
     * Set middleware to quard controller.
     *
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

        $instructions =  Instruction::orderBy('title','ASC')->get();

		return view('admin.instructions.index', ['instructions'=>$instructions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::orderBy('name','ASC')->get();

        return view('admin.instructions.create', ['departments'=>$departments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstructionRequest $request)
    {
        
        $departments = $request['department_id'];
        $employees = Registration::where('odjava',null)->get();
      
        $employee_departments = Employee_department::get();
        $emails = array();
        $mail_to_employees = array();

        foreach ($departments as $department_id) {
            $data = array(
                'department_id' => $department_id,
                'title'         => $request['title'],
                'description'   => $request['description'],
                'active'   => $request['active']
            );
            
            $instruction = new Instruction();
            $instruction->saveInstruction($data);
        }
        
        foreach ($departments as $department_id) {
            $department = Department::where('id', $department_id )->first();
         
			if($department->level == 0 && $department->name != 'Uprava' ) {
				foreach ($employees as $employee) {
					if($employee->employee['email'] != null) {
						array_push($emails, $employee->employee['email']);
					}
				}
			} else if ($department->level == 1) {
				$departments_level2 = $departments->where('level1', $department->id );

				foreach ($departments_level2 as $department_level2) {
					$employees_dep = $employee_departments->where('department_id', $department_level2->id );

					foreach ($employees_dep as $employee_dep) {
						array_push($mail_to_employees, $employee_dep);
					}
				}
				foreach ($mail_to_employees as $to_employee) {
					if($employees->where('employee_id', $to_employee->employee_id)->first() && $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email'] != null ) {
						array_push($emails, $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email']);
					}
				}
			} else  {
				$department_level2 = $department->where('id', $department->id )->first();
				$employees_dep = $employee_departments->where('department_id', $department_level2->id );

				foreach ($employees_dep as $employee_dep) {
					array_push($mail_to_employees, $employee_dep);
				}
				foreach ($mail_to_employees as $to_employee) {
					if($employees->where('employee_id', $to_employee->employee_id)->first() && $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email'] != null ) {
						array_push($emails, $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email']);
					}
				}
			}
        }
        

		$link = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/instructions/'. $instruction->id ;
        $title = $instruction->title;
        
      
        try {
			foreach(array_unique($emails) as $email_to_employee) {
				Mail::queue(
					'email.instruction',
					['link' => $link],
					function ($message) use ($email_to_employee, $title) {
						$message->to($email_to_employee)
							->from('info@duplico.hr', 'Duplico')
							->subject('Radna uputa - ' . $title);
					}
				);
			}
		} catch (\Throwable $th) {
			$message = session()->flash('error', 'Mail nije poslan, došlo je do problema prilikom slanja.');
			return redirect()->back()->withFlashMessage($message);
        }
      
        

        $message = session()->flash('success', 'Nova radna uputa je spremljena');
        
		return redirect()->route('admin.instructions.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $instruction = Instruction::find($id);
        $employee = Employee::where('first_name', Sentinel::getUser()->first_name)->where('last_name', Sentinel::getUser()->last_name)->first();
        $uprava = false;
        if($employee) {
            $employee_departments = array();
            foreach ( $employee->departments as $employee_department) {
                array_push($employee_departments,$employee_department->department['name']);
            }
    
            if(in_array('Uprava', $employee_departments)) {
                $uprava = true;
            }
        }
       
        return view('admin.instructions.show', ['instruction'=>$instruction, 'uprava'=> $uprava]);
    }

    public function show_instructions()
     {
        $employee = Employee::where('first_name', Sentinel::getUser()->first_name)->where('last_name', Sentinel::getUser()->last_name)->first();
        $instructions = Instruction::where('active',1)->orderBy('title', 'ASC')->get();
        
        if ($employee ) {
            $reg_employee = Registration::where('employee_id', $employee->id)->first();
            
            $employee_departments = array();
            $employee_instructions = collect();
    
            if ($reg_employee) {
                $departments = Employee_department::where('employee_id',$employee->id )->get();
                    foreach( $departments as $department) {
                        array_push($employee_departments,$department->department_id);
                        array_push($employee_departments, 10); // odjel "svi"
                        if ($department->level == 2 ) {
                            array_push($employee_departments,$department->level1);
                        } 
                    }
                    $employee_departments =  array_unique($employee_departments);
                   
                    foreach ($employee_departments as $department_id) {
                        $employee_instructions = $employee_instructions->merge($instructions->where('department_id', $department_id));
    
                    }   
                    $employee_instructions =  $employee_instructions->unique('title');
            } else {
                $employee_instructions =  $instructions->where('department_id' ,10);
            }
        } else {
            $employee_instructions =  $instructions->where('department_id' ,10);
        }
        
        return view('admin.show_instructions', ['employee_instructions'=>$employee_instructions]);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $instruction = Instruction::find($id);
        $departments = Department::orderBy('name','ASC')->get();

        return view('admin.instructions.edit', ['instruction' => $instruction, 'departments' => $departments ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstructionRequest $request, $id)
    {
        $instruction = Instruction::find($id);

        $data = array(
            'department_id' => $request['department_id'],
            'title'         => $request['title'],
            'active'   => $request['active'],
            'description'   => $request['description']
        );
        
        $instruction->updateInstruction($data);
		
        $message = session()->flash('success', 'Uputa je ispravljena');
        
		return redirect()->route('admin.instructions.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $instruction = Instruction::find($id);
        $instruction->delete();
		
		$message = session()->flash('success', 'Uputa je uspješno obrisano');
		
        return redirect()->route('admin.instructions.index')->withFlashMessage($message);
        
    }
}
