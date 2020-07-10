<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Registration;
use App\Models\Project;
use App\Models\MeetingRoom;
use App\Http\Requests\MeetingRequest;
use DateTime;

class MeetingController extends Controller
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
        $meetings = Meeting::get();

		return view('admin.meetings.index',['meetings' => $meetings]);
    }

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showKalendar(Request $request)
    {
		$input = $request;
		
		$list = array();
		$datum = new DateTime('now');
		
		if(strpos($input->mjesec, '-')){
			$mjesec = strstr( $input['mjesec'],'-',true);
			$godina = substr( $input['mjesec'],'-4');
		} else {
			$mjesec = date_format($datum,'m');
			$godina = date_format($datum,'Y');
		}

		if($mjesec){
			for($d=1; $d<=31; $d++)
			{
				$time=mktime(12, 0, 0, $mjesec, $d, $godina);  
				if (date('m', $time)==$mjesec){
					$list[]=date('Y/m/d/D', $time);
				}
			}
		
			$meetings = Meeting::get();
			$rooms = MeetingRoom::get();
			
			return view('admin.showKalendar',['meetings' => $meetings])->with('list', $list)->with('mjesec', $mjesec)->with('godina', $godina)->with('rooms', $rooms);
		} else {

			$message = session()->flash('error', 'Odaberi mjesec');
			return redirect()->back()->withFlashMessage($message);
		}
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$registrations = Registration::join('employees','registrations.employee_id','employees.id')->select('employees.first_name','employees.last_name','registrations.*')->orderBy('employees.last_name','ASC')->get();
		$projects = Project::orderBy('id','ASC')->get();
		$meeting_rooms = MeetingRoom::get();

		return view('admin.meetings.create',['registrations' => $registrations],['projects' => $projects])->with('meeting_rooms',$meeting_rooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	 
    public function store(MeetingRequest $request)
    {
        $input = $request->except(['_token']);
			
		$data = array(
			'datum'  			=>date("Y-m-d", strtotime($input['datum'])),
			'start_time'  		=> $input['start_time'],
			'end_time'  		=> $input['end_time'],
			'employee_id'   	=> $input['employee_id'],
			'meeting_room_id'   => $input['meeting_room_id'],
			'project_id'  		=> $input['project_id'],
			'description' 		=> $input['description']
		);
		
		$meeting = new Meeting();
		$meeting->saveMeeting($data);
		
		$datum = new DateTime('now');
		$mjesec = date_format($datum,'m');
		$godina = date_format($datum,'Y');
		
		if($mjesec){
			for($d=1; $d<=31; $d++){
				$time=mktime(12, 0, 0, $mjesec, $d, $godina);  
				if (date('m', $time)==$mjesec){
					$list[]=date('Y/m/d/D', $time);
				}
			}
		}
		
		$meetings = Meeting::get();
		$rooms = MeetingRoom::get();
		
		$message = session()->flash('success', 'Sastanak je dodan.');
		
		return redirect()->route('admin.showKalendar',['meetings' => $meetings,'list' => $list,'mjesec' =>$mjesec,'godina' => $godina,'rooms' =>$rooms])->withFlashMessage($message);
		
		//return redirect()->back()->withFlashMessage($messange);
		//return redirect()->route('admin.meetings.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $meeting = Meeting::find($id);
		$registrations = Registration::join('employees','registrations.employee_id','employees.id')->select('employees.first_name','employees.last_name','registrations.*')->orderBy('employees.last_name','ASC')->get();
		$projects = Project::orderBy('id','ASC')->get();
		$meeting_rooms = MeetingRoom::get();
		
		return view('admin.meetings.edit',['meeting'=>$meeting])->with('registrations',$registrations)->with('projects',$projects)->with('meeting_rooms',$meeting_rooms);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MeetingRequest $request, $id)
    {
        $meeting = Meeting::find($id);
		$input = $request->except(['_token']);

		$data = array(
			'datum'  			=>date("Y-m-d", strtotime($input['datum'])),
			'start_time'  		=> $input['start_time'],
			'end_time'  		=> $input['end_time'],
			'employee_id'   	=> $input['employee_id'],
			'meeting_room_id'   => $input['meeting_room_id'],
			'project_id'  		=> $input['project_id'],
			'description' 		=> $input['description']
		);
		
		$meeting->updateMeeting($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.meetings.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meeting = Meeting::find($id);
		$meeting->delete();
		
		$message = session()->flash('success', 'sastanak je obrisan.');
		
		return redirect()->route('admin.meetings.index')->withFlashMessage($message);
    }
}
