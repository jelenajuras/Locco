<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeTraining;
use App\Models\Registration;
use App\Models\EmployeeTermination;
use App\Models\Training;

class EmployeeTrainingController extends Controller
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
        $employeeTrainings = EmployeeTraining::orderBy('expiry_date','DESC')->get();
		
		return view('admin.employee_trainings.index',['employeeTrainings'=>$employeeTrainings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		$terminations = EmployeeTermination::get();
		$trainings = Training::orderBy('name','ASC')->get();
		
		return view('admin.employee_trainings.create',['registrations'=>$registrations, 'terminations'=>$terminations, 'trainings'=>$trainings ]);
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

		$data = array(
			'employee_id'  	=> $input['employee_id'],
			'training_id'  	=> $input['training_id'],
			'date'  		=> $input['date'],
			'expiry_date'  	=> $input['expiry_date'],
			'description'  	=> $input['description']
		);
		
		$employeeTrainings = new EmployeeTraining();
		$employeeTrainings->saveEmployeeTraining($data);
		
		$message = session()->flash('success', 'Novo osposobljavanje je spremljeno');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.employee_trainings.index')->withFlashMessage($message);
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
		$employeeTraining = EmployeeTraining::find($id);
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		$terminations = EmployeeTermination::get();
		$trainings = Training::orderBy('name','ASC')->get();
		
		return view('admin.employee_trainings.edit', ['employeeTraining' => $employeeTraining, 'registrations'=>$registrations, 'terminations'=>$terminations, 'trainings'=>$trainings]);
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
        $employeeTraining = EmployeeTraining::find($id);
		$input = $request->except(['_token']);

		$data = array(
			'employee_id'  	=> $input['employee_id'],
			'training_id'  	=> $input['training_id'],
			'date'  		=> $input['date'],
			'expiry_date'  	=> $input['expiry_date'],
			'description'  	=> $input['description']
		);
		
		$employeeTraining->updateEmployeeTraining($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.employee_trainings.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeeTraining = EmployeeTraining::find($id);
		$employeeTraining->delete();
		
		$message = session()->flash('success', 'Osposobljavanje je obrisano.');
		
		return redirect()->route('admin.employee_trainings.index')->withFlashMessage($message);
    }
}
