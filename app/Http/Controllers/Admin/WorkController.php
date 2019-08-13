<?php

namespace App\Http\Controllers\Admin;

use App\Models\Work;
use App\Models\Users;
use App\Models\Employee;
use App\Models\Registration;
use App\Models\Termination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use Sentinel;

class WorkController extends Controller
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
    public function index()
    {
        $works = Work::orderBy('odjel','ASC')->orderBy('naziv','ASC')->paginate(100);
				
		return view('admin.works.index',['works'=>$works]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	   $users = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
	   $terminations = Termination::get();
	   
	   return view('admin.works.create',['users'=>$users, 'terminations'=>$terminations]);
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
			'odjel'  => $input['odjel'],
			'naziv'  => $input['naziv'],
			'job_description'  => $input['job_description'],
			'pravilnik'  => $input['pravilnik'],
			'tocke'  => $input['tocke'],
			'user_id'  => $input['user_id']
		);
		
		if($input['prvi_userId']){
			$data += ['prvi_userId' => $input['prvi_userId']];
		}
		if($input['drugi_userId']){
			$data += ['drugi_userId' => $input['drugi_userId']];
		}
		
		$work = new Work();
		$work->saveWork($data);
		
		$message = session()->flash('success', 'Dodano je novo radno mjesto');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.works.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $work = Work::find($id);
		
		return view('admin.works.show', ['work' => $work]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$work1 = Work::find($id);
		
		$work = Work::leftjoin('employees','employees.id','works.user_id')->find($id);
		$users = Registration::join('employees','registrations.employee_id','employees.id')->select('registrations.*','employees.last_name','employees.first_name')->orderBy('last_name','ASC')->get();

	    $terminations = Termination::get();
	   
		return view('admin.works.edit',['work' => $work, 'work1' => $work1, 'users' => $users, 'terminations' => $terminations]);
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
        $work = Work::find($id);
		$input = $request->except(['_token']);

		$data = array(
			'odjel'  => $input['odjel'],
			'naziv'  => $input['naziv'],
			'job_description'  => $input['job_description'],
			'pravilnik'  => $input['pravilnik'],
			'tocke'  => $input['tocke'],
			'user_id'  => $input['user_id']
		);
		
		if($input['prvi_userId']){
			$data += ['prvi_userId' => $input['prvi_userId']];
		} else {
			$data += ['prvi_userId' => null];
		}
		if($input['drugi_userId']){
			$data += ['drugi_userId' => $input['drugi_userId']];
		} else {
			$data += ['drugi_userId' => null];
		}
		
		$work->updateWork($data);
		
		$message = session()->flash('success', 'Radno mjesto je ispravljeno');
		
		return redirect()->route('admin.works.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $work = Work::find($id);
		$work->delete();
		
		$message = session()->flash('success', 'Radno mjesto je obrisano.');
		
		return redirect()->route('admin.works.index')->withFlashMessage($message);
    }
}
