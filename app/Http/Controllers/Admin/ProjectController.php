<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Controllers\Controller;
use Sentinel;

class ProjectController extends Controller
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
		$projects = Project::orderBy('active','DESC')->orderBy('erp_id','ASC')->get();
		
		return view('admin.projects.index',['projects'=>$projects]);
    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::orderBy('naziv','ASC')->get();
		$employees = Employee::orderBy('last_name','ASC')->get();
		return view('admin.projects.create')->with('customers', $customers)->with('employees', $employees);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProjektRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // $user_id = Sentinel::getUser()->id;
		$input = $request;

		if(isset($input['investitor_id']) && $input['investitor_id'] == ''){
			$input['investitor_id'] = $input['customer_id'];
		}
		
		$data = array(
			'erp_id'         => $input['erp_id'],
			/* 'customer_id'    => $input['customer_id'],
			'investitor_id'  => $input['investitor_id'], */
            'naziv' 		 => $input['naziv'],
            'customer_oib'    => $input['customer_oib'],
			'objekt' 		 => $input['objekt'],
			'active' 		 => '1'
		);
	
		if($input['user_id']){
			$data += ['user_id' => $input['user_id']];
		}

		$project = new Project();
		$project->saveProject($data);
		
		$message = session()->flash('success', 'Uspješno je dodan novi projekt');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.projects.index')->withFlashMessage($message);
    }
	
	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
		
		return view('admin.projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
		$customers = Customer::orderBy('naziv','ASC')->get();
		$employees = Employee::orderBy('last_name','ASC')->get();
		
		return view('admin.projects.edit', ['project' => $project])->with('customers', $customers)->with('employees', $employees);
	
    }
	 public function update(ProjectRequest $request, $id)
    {
        $project = Project::find($id);
		$input = $request;

		$data = array(
			'erp_id'         => $input['erp_id'],
			/* 'customer_id'    => $input['customer_id'],
			'investitor_id'  => $input['investitor_id'], */
            'naziv' 		 => $input['naziv'],
            'customer_oib'    => $input['customer_oib'],
			'objekt' 		 => $input['objekt'],
			'active' 		 => '1'
		);
		
		$project->updateProject($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci projekta');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.projects.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
		$project ->delete();
		
		$message = session()->flash('success', 'Projekt je uspješno obrisan');
		
		return redirect()->route('admin.projects.index')->withFlashMessage($message);
    }
}