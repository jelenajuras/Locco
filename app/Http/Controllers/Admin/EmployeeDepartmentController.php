<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Department;
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
    public function create()
    {
        $departments = Department::get();
        $empl_departments = Employee_department::get();
		$registrations = Registration::join('employees','employees.id','registrations.employee_id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		return view('admin.employee_departments.create',['empl_departments'=>$empl_departments, 'departments'=>$departments, 'registrations'=>$registrations]);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token']);

		foreach($input['department_id'] as $department) {
			$data = array(
			'employee_id'  		=> $input['employee_id'],
			'department_id'     => $department
			);
			$employeeDepartment = new Employee_department();
			$employeeDepartment->saveEmployeeDepartment($data);
		}
	
		$message = session()->flash('success', 'Novi odjel je snimljen');
		
		return redirect()->route('admin.employee_departments.create')->withFlashMessage($message);
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
        $input = $request->except(['_token']);
	    $employeeDepartments = Employee_department::where('employee_id',$request['employee_id'])->get();
		if($employeeDepartments){
			foreach($employeeDepartments as $employeeDepartment){
				$this->destroy($employeeDepartment->id);
			}
		}

		foreach($input['department_id'] as $department) {
			$data = array(
			'employee_id'  		=> $input['employee_id'],
			'department_id'     => $department
			);
			$employeeDepartment = new Employee_department();
			$employeeDepartment->saveEmployeeDepartment($data);
		}

		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.employee_departments.create')->withFlashMessage($message);
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
