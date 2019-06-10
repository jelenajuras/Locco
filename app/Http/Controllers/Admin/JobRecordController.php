<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JobRecord;
use App\Http\Requests\JobRecordRequest;
use App\Models\EmployeeTermination;
use App\Models\Registration;

class JobRecordController extends Controller
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
        $job_records = JobRecord::get();
			
        return view('admin.job_records.index',['job_records'=>$job_records]);
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
		
		return view('admin.job_records.create',['registrations'=>$registrations, 'terminations'=>$terminations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobRecordRequest $request)
    {
        $input = $request->except(['_token']);

		$data = array(
			'employee_id'  	=> $input['employee_id'],
			'date'  		=> $input['date'],
			'task'  		=> $input['task'],
			'odjel'  		=> $input['odjel'],
			'time'  		=> $input['time'],
			'task_manager'  => $input['task_manager']
		);
		
		$jobRecord = new JobRecord();
		$jobRecord->saveJobRecord($data);
		
		$message = session()->flash('success', 'Novi zadatak je dodan');

		return redirect()->route('admin.job_records.index')->withFlashMessage($message);
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
        $job_record = JobRecord::find($id);
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		$terminations = EmployeeTermination::get();
		
		return view('admin.job_records.edit', ['job_record' => $job_record,'registrations' => $registrations,'terminations' => $terminations]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JobRecordRequest $request, $id)
    {
        $job_record = JobRecord::find($id);
		$input = $request->except(['_token']);
		
		$data = array(
			'employee_id'  	=> $input['employee_id'],
			'date'  		=> $input['date'],
			'task'  		=> $input['task'],
			'odjel'  		=> $input['odjel'],
			'time'  		=> $input['time'],
			'task_manager'  => $input['task_manager']
		);
		
		$job_record->updateJobRecord($data);
		
		$message = session()->flash('success', 'Zadatak je ispravljen');

		return redirect()->route('admin.job_records.index')->withFlashMessage($message);
		 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $job_record = JobRecord::find($id);
	   
	   $job_record->delete();
		
		$message = session()->flash('success', 'Zadatak je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);
    }
	
}
