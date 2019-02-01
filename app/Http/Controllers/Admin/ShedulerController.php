<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheduler;
use App\Models\Registration;
use App\Models\VacationRequest;
use App\Http\Controllers\GodisnjiController;

class ShedulerController extends Controller
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
        $requests = VacationRequest::join('employees','vacation_requests.employee_id','employees.id')->select('vacation_requests.*', 'employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		return view('admin.shedulers.index')->with('requests',$requests);
		
		
		/*$shedulers = Sheduler::get();
		$employees = Registration::join('employees','registrations.employee_id','employees.id')->select('registrations.*','employees.first_name', 'employees.last_name')->orderBy('last_name','ASC')->get();
		
		return view('admin.shedulers.index')->with('shedulers',$shedulers)->with('employees',$employees);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$input = $request;
		
		//$employees = Registration::join('employees','registrations.employee_id','employees.id')->select('registrations.*','employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		$employees = Registration::join('employees','registrations.employee_id','employees.id')->leftJoin('employee_terminations','registrations.employee_id', '=', 'employee_terminations.employee_id')->select('registrations.*','employees.first_name', 'employees.last_name', 'employee_terminations.datum_odjave')->orderBy('employees.last_name','ASC')->get();

		$requests = VacationRequest::join('employees','vacation_requests.employee_id','employees.id')->select('vacation_requests.*', 'employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		$list = array();
			$mjesec = strstr( $input['mjesec'],'-',true);
			$godina = substr( $input['mjesec'],'-4');

			for($d=1; $d<=31; $d++)
			{
				$time=mktime(12, 0, 0, $mjesec, $d, $godina);  
				if (date('m', $time)==$mjesec){   
						$list[]=date('Y/m/d/D', $time);
				}
			}

		return view('admin.shedulers.create')->with('employees',$employees)->with('mjesec', $mjesec)->with('godina', $godina)->with('list', $list)->with('requests', $requests);
    
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
    public function show(Request $request)
    {
		$requests = VacationRequest::join('employees','vacation_requests.employee_id','employees.id')->select('vacation_requests.*', 'employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		return view('admin.shedulers.index')->with('requests',$requests);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
	
	public function shedule (Request $request)
	{
		$input = $request;
		$employees = Registration::join('employees','registrations.employee_id','employees.id')->select('registrations.*','employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		
		$requests = VacationRequest::join('employees','vacation_requests.employee_id','employees.id')->select('vacation_requests.*', 'employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		$list = array();
		$mjesec = strstr( $input['mjesec'],'-',true);
		$godina = substr( $input['mjesec'],'-4');

		for($d=1; $d<=31; $d++)
		{
			$time=mktime(12, 0, 0, $mjesec, $d, $godina);  
			if (date('m', $time)==$mjesec){   
					$list[]=date('Y/m/d/D', $time);
			}
		}
	
		return view('admin.shedule')->with('employees',$employees)->with('mjesec', $mjesec)->with('godina', $godina)->with('list', $list)->with('requests', $requests);
		
	}
}
