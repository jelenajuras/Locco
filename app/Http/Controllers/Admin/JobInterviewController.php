<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JobInterview;
use App\Models\Work;
use App\Http\Requests\JobInterviewRequest;
use Sentinel;

class JobInterviewController extends Controller
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
		$job_interviews = JobInterview::get();
		$works = Work::orderBy('odjel','ASC')->orderBy('naziv','ASC')->get();
		
        return view('admin.job_interviews.index',['job_interviews'=>$job_interviews],['works'=>$works]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $works = Work::get();
		
		return view('admin.job_interviews.create',['works'=>$works]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobInterviewRequest $request)
    {
        $input = $request->except(['_token']);
		//dd($input);
		if($input['placa'] == ''){
			$placa = '0';
		}else {
			$placa = $input['placa'];
		}
		
		if($input['godine_iskustva'] == ''){
			$godine = '0';
		}else {
			$godine = $input['godine_iskustva'];
		}	
		
		$data = array(
			'first_name'  		=> $input['first_name'],
			'last_name'  		=> $input['last_name'],
			'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
			'oib'  				=> $input['oib'],
			'email'  			=> $input['email'],
			'telefon'  			=> $input['telefon'],
			'sprema'  			=> $input['sprema'],
			'zvanje'  			=> $input['zvanje'],
			'radnoMjesto_id'  	=> $input['radnoMjesto_id'],
			'godine_iskustva'  	=> $godine,
			'placa'  			=> $placa,
			'jezik'  			=> $input['jezik'],
			'napomena'  		=> $input['napomena']
		);
		
		$jobInterview = new JobInterview();
		$jobInterview->saveJobInterview($data);
		
		$job_interviews = JobInterview::get();
		return view('admin.job_interviews.index',['job_interviews'=>$job_interviews]);
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
        $job_interview = JobInterview::find($id);
		$works = Work::get();
		
		return view('admin.job_interviews.edit', ['job_interview' => $job_interview], ['works' => $works]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JobInterviewRequest $request, $id)
    {
        $job_interview = JobInterview::find($id);
		 
		$input = $request->except(['_token']);
		//dd($input);
		if($input['placa'] == ''){
			$placa = '0';
		}else {
			$placa = $input['placa'];
		}
		
		if($input['godine_iskustva'] == ''){
			$godine = '0';
		}else {
			$godine = $input['godine_iskustva'];
		}	
		
		$data = array(
			'first_name'  		=> $input['first_name'],
			'last_name'  		=> $input['last_name'],
			'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
			'oib'  				=> $input['oib'],
			'email'  			=> $input['email'],
			'telefon'  			=> $input['telefon'],
			'sprema'  			=> $input['sprema'],
			'zvanje'  			=> $input['zvanje'],
			'radnoMjesto_id'  	=> $input['radnoMjesto_id'],
			'godine_iskustva'  	=> $godine,
			'placa'  			=> $placa,
			'jezik'  			=> $input['jezik'],
			'napomena'  		=> $input['napomena']
		);
		 
		$job_interview->updateJobInterview($data);
		
		$job_interviews = JobInterview::get();
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
			
		return redirect()->route('admin.job_interviews.index', ['job_interviews' => $job_interviews])->withFlashMessage($message);
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job_interview = JobInterview::find($id);
		$job_interview->delete();
		
		$message = session()->flash('success', 'Zahtjev je obrisan.');
		
		return redirect()->route('admin.job_interviews.index')->withFlashMessage($message);
    }
}
