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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request;
       
		$employees = Registration::join('employees','registrations.employee_id','employees.id')->leftJoin('employee_terminations','registrations.employee_id', '=', 'employee_terminations.employee_id')->select('registrations.*','employees.first_name', 'employees.last_name', 'employee_terminations.datum_odjave')->orderBy('employees.last_name','ASC')->get();
 
        $list = array();
        $datum = explode('-',$request['mjesec']);
		$godina = $datum[0];
        $mjesec = $datum[1];
        if( $mjesec > 1) {
            $requests = VacationRequest::join('employees','vacation_requests.employee_id','employees.id')->select('vacation_requests.*', 'employees.first_name', 'employees.last_name')->where('odobreno','DA')->whereMonth('start_date','>=',$mjesec-1 )->whereYear('start_date', $godina )->orderBy('employees.last_name','ASC')->get();
        } else {
            $requests = VacationRequest::join('employees','vacation_requests.employee_id','employees.id')->select('vacation_requests.*', 'employees.first_name', 'employees.last_name')->where('odobreno','DA')->whereMonth('start_date','=', $mjesec )->whereYear('start_date', '=', $godina )->orderBy('employees.last_name','ASC')->get();
            $requests2 = VacationRequest::join('employees','vacation_requests.employee_id','employees.id')->select('vacation_requests.*', 'employees.first_name', 'employees.last_name')->where('odobreno','DA')->whereMonth('start_date','=', 12 )->whereYear('start_date', '=', $godina-1 )->orderBy('employees.last_name','ASC')->get();
            foreach($requests2 as $request2) {
                $requests->push( $request2);
            }
        }
       
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
		$employees = Registration::join('employees','registrations.employee_id','employees.id')->select('registrations.*','employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->where('odjava',null)->get();

        $datum = explode('-', $request['mjesec']);
		$godina = $datum[0];
        $mjesec = $datum[1];
       
		return view('admin.shedule')->with('employees',$employees)->with('mjesec', $mjesec)->with('godina', $godina);
		
	}
}
