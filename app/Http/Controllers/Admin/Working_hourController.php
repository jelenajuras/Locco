<?php

namespace App\Http\Controllers\Admin;

use App\Models\Working_hour;
use App\Models\Registration;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Working_hourRequest;
use App\Http\Controllers\Controller;
use Sentinel;

class Working_hourController extends Controller
{
     public function __construct()
    {
        $this->middleware('sentinel.auth');
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
 		$working_hours = Working_hour::get();
		$djelatnici = Registration::join('employees','employee_id','employees.id')->select('registrations.*','employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();

		$list = array();
			$month= 5;
			$year = 2018;

			for($d=1; $d<=31; $d++)
			{
				$time=mktime(12, 0, 0, $month, $d, $year);  
				if (date('m', $time)==$month){   
						$list[]=date('Y/m/d/D', $time);
				}
			}

		return view('admin.workingHours.index')->with('working_hours',$working_hours)->with('djelatnici', $djelatnici)->with('list', $list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$input = $request->except(['_token']);

		$djelatnici = Registration::join('employees','registrations.employee_id','employees.id')->select('registrations.*','employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		$list = array();
			$mjesec= strstr( $input['mjesec'],'-',true);
			$godina = substr( $input['mjesec'],'-4');

			for($d=1; $d<=31; $d++)
			{
				$time=mktime(12, 0, 0, $mjesec, $d, $godina);  
				if (date('m', $time)==$mjesec){   
						$list[]=date('Y/m/d/D', $time);
				}
			}
		$vacations = VacationRequest::get();
		
		//dd($vacations);
		
		return view('admin.workingHours.create')->with('djelatnici', $djelatnici)->with('mjesec', $mjesec)->with('godina', $godina)->with('list', $list)->with('vacations', $vacations);
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
		
	/*	$i=1;
		$data=array();
		foreach($input as $key => $value){
			if($key == 'dan_'.$i) {
				
				array_push($data, $value);
				
			}
			if($key == 'ime'.$i) {
				$user = $value;
				array_push($data, $user);
			}
			if($key ==  'ozn'.$i) {
				$status = $value;
				array_push($data, $status);
			}
		}
		
		dd($array);*/
		
		
		/*$data = array( $data
					'user_id'  => $user,
					'status'   => $status
					);	
				array_push($array, $data);
				$i++;*/
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
}
