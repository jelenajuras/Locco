<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EvaluatingEmployee;
use App\Models\Registration;
use App\Models\Questionnaire;
use App\Http\Requests\EvaluatingEmployeeRequest;

class EvaluatingEmployeeController extends Controller
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
        $evaluatingEmployees = EvaluatingEmployee::get();
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();

		return view('admin.evaluating_employees.index',['registrations'=>$registrations, 'evaluatingEmployees'=>$evaluatingEmployees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		$employee = Registration::where('employee_id',$request->id)->first();
		$questionnaires = Questionnaire::get();
		
		return view('admin.evaluating_employees.create',['registrations'=>$registrations, 'employee'=>$employee, 'questionnaires'=>$questionnaires]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluatingEmployeeRequest $request)
    {
		$input = $request;
		
		foreach($input['ev_employee_id'] as $ev_employee_id){
			$data = array(
				'employee_id'  	 	=> $input['employee_id'],
				'ev_employee_id'  	=> $ev_employee_id,
				'mjesec_godina'  	=> $input['mjesec_godina'],
				'questionnaire_id'  => $input['questionnaire_id']
			);
			
			$evaluatingEmployee = new EvaluatingEmployee();
			$evaluatingEmployee->saveEvaluatingEmployee($data);
		}

		$message = session()->flash('success', 'Uspješno su dodatni zaposlenici');
		
		return redirect()->route('admin.evaluating_employees.index')->withFlashMessage($message);
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
    public function edit(Request $request, $employee_id)
    {
		$evaluatingEmployee = EvaluatingEmployee::where('id',$request['ev_empl_id'])->first(); 
		$questionnaire_id = $evaluatingEmployee->questionnaire_id;
		$evaluatingEmployees = EvaluatingEmployee::where('employee_id',$evaluatingEmployee->employee_id)->where('questionnaire_id',$evaluatingEmployee->questionnaire_id)->get(); //svi zapisi za istu anketu istog djelatnika

		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		$employee = Registration::where('employee_id',$employee_id)->first();
	
		$questionnaires = Questionnaire::get();
		
		return view('admin.evaluating_employees.edit',['evaluatingEmployee'=>$evaluatingEmployee, 'evaluatingEmployees'=>$evaluatingEmployees, 'registrations'=>$registrations, 'employee'=>$employee, 'questionnaires'=>$questionnaires]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluatingEmployeeRequest $request, $id)
    {
	    $input = $request;
	    
		$evaluatingEmployees = EvaluatingEmployee::where('employee_id',$input['employee_id'])->where('mjesec_godina',$input['mjesec_godina'])->where('questionnaire_id',$input['questionnaire_id'])->get();
	   
		foreach($evaluatingEmployees as $evaluatingEmployee){
		   $evaluatingEmployee->delete();
	    }
		
		foreach($input['ev_employee_id'] as $ev_employee_id){
			$data = array(
				'employee_id'  	 	=> $input['employee_id'],
				'ev_employee_id'  	=> $ev_employee_id,
				'mjesec_godina'  	=> $input['mjesec_godina'],
				'questionnaire_id'  => $input['questionnaire_id']
			);
			
			$evaluatingEmployee1 = new EvaluatingEmployee();
			$evaluatingEmployee1->saveEvaluatingEmployee($data);
		}

	    $message = session()->flash('success', 'Podaci su ispravljeni!');
	    return redirect()->route('admin.evaluating_employees.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluatingEmployee = EvaluatingEmployee::find($id);
		
		$evaluatingEmployee->delete();
		
		$message = session()->flash('success', 'Obrisano ospješno');
		
		return redirect()->route('admin.evaluating_employees.index')->withFlashMessage($message);
    }
}
