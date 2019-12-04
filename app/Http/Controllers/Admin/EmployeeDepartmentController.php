<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Employee_department;
use App\Http\Controllers\Controller;

class EmployeeDepartmentController extends Controller
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
		$departments = Department::get();
        $empl_departments = Employee_department::get();

		$registrations = Registration::join('employees','employees.id','registrations.employee_id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();

		return view('admin.employee_departments.index',['empl_departments'=>$empl_departments, 'departments'=>$departments, 'registrations'=>$registrations]);
    }
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
 
        $employee = Employee::where('id', $request['employee_id'])->first();
        $empl_departments = Employee_department::get();
        $employee_departments = $empl_departments->where('employee_id', $employee->id );
        $departments = Department::get();
        
		return view('admin.employee_departments.create',['employee'=>$employee,'empl_departments'=>$empl_departments, 'departments'=>$departments, 'employee_departments'=>$employee_departments]);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employeeDepartments = Employee_department::where('employee_id',$request['employee_id'])->get();
        if($employeeDepartments){
            foreach($employeeDepartments as $employeeDepartment){
                $this->destroy($employeeDepartment->id);
            }
        }
        if(is_array( $request['department_id'])) {
            foreach($request['department_id'] as $department_id) {
                $data = array(
                    'employee_id'  		=> $request['employee_id'],
                    'department_id'     => $department_id
                );
                $employeeDepartment = new Employee_department();
                $employeeDepartment->saveEmployeeDepartment($data);
            }
        } else if (isset( $request['department_id'])){
            $data = array(
                'employee_id'  		=> $request['employee_id'],
                'department_id'     =>  $request['department_id'],
            );
            $employeeDepartment = new Employee_department();
            $employeeDepartment->saveEmployeeDepartment($data);
        }

		$message = session()->flash('success', 'Odjeli su snimljeni');
		
		return redirect()->back()->withFlashMessage($message);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = Department::find($id);
        $employeeDepartments = Employee_department::where('department_id',$department->id)->get();
		$registrations = Registration::join('employees','employees.id','registrations.employee_id')->select('registrations.*','employees.first_name','employees.last_name')->where('odjava',null)->orderBy('employees.last_name','ASC')->get();

		return view('admin.employee_departments.edit', ['employeeDepartments' => $employeeDepartments,'department'=>$department, 'registrations'=>$registrations]);
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
        if(is_array( $request['employee_id'])) {
            $employeeDepartments = Employee_department::where('department_id',$request['department_id'])->get();
            if($employeeDepartments){
                foreach($employeeDepartments as $employeeDepartment){
                    $this->destroy($employeeDepartment->id);
                }
            }

            foreach($request['employee_id'] as $employee_id) {
                $data = array(
                    'employee_id'  		=> $employee_id,
                    'department_id'     => $request['department_id']
                );
                $employeeDepartment = new Employee_department();
                $employeeDepartment->saveEmployeeDepartment($data);
            }
        } else {
            $data = array(
                'employee_id'  		=> $request['employee_id'],
                'department_id'     => $request['department_id']
            );
            $employeeDepartment = new Employee_department();
            $employeeDepartment->saveEmployeeDepartment($data);
        }

		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	 /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee_department = Employee_department::find($id);
		$employee_department->delete();
		
		//$message = session()->flash('success', 'Odjel je obrisan.');
		//return redirect()->route('admin.employee_departments.index')->withFlashMessage($message);
		//return redirect()->route('admin.employee_departments.index');
    }
	
}
