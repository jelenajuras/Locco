<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\EvaluatingGroup;
use App\Models\EvaluatingQuestion;
use App\Models\EvaluatingRating;
use App\Models\EvaluatingEmployee;
use App\Models\Evaluation;
use App\Models\Employee;
use App\Models\Registration;
use App\Http\Requests\QuestionnaireRequest;
use Sentinel;
use DateTime;

class QuestionnaireController extends Controller
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
        $questionnaires = Questionnaire::get();
		
		return view('admin.questionnaires.index',['questionnaires'=>$questionnaires]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.questionnaires.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$input = $request;

		$data = array(
			'naziv'  	 => $input['naziv'],
			'opis'  	 => $input['opis'],
			'status'  	 => $input['status']
		);
			
		$questionnaire = new Questionnaire();
		$questionnaire->saveQuestionnaire($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova anketa');
		
		return redirect()->route('admin.questionnaires.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Sentinel::getUser();
		
		$danas = new DateTime('now');
		$mjesec_godina = date_format($danas,'Y-m');
		
		$employee = Employee::where('first_name', $user->first_name)->where('last_name', $user->last_name)->first();
				
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
			//svi djelatnici
		
	    $questionnaire = Questionnaire::find($id);
		$evaluatingGroups = EvaluatingGroup::where('questionnaire_id', $questionnaire->id)->get();
		$evaluatingQuestion = EvaluatingQuestion::get();
		$evaluatingRatings = EvaluatingRating::get();
		$evaluatingEmployees = EvaluatingEmployee::where('employee_id', $employee->id)->where('status', null)->where('mjesec_godina',$mjesec_godina)->get();
		
		return view('admin.questionnaires.show',['employee'=>$employee,'registrations'=>$registrations,'evaluatingEmployees'=>$evaluatingEmployees,'questionnaire'=>$questionnaire,'evaluatingGroups'=>$evaluatingGroups,'evaluatingQuestion'=>$evaluatingQuestion,'evaluatingRatings'=>$evaluatingRatings]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $questionnaire = Questionnaire::find($id);
		 
		return view('admin.questionnaires.edit',['questionnaire'=>$questionnaire]);
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
        $questionnaire = Questionnaire::find($id);
		$input = $request;
		
		$data = array(
			'naziv'  	 => $input['naziv'],
			'opis'  	 => $input['opis'],
			'status'  	 => $input['status']
		);
		
		$questionnaire->updateQuestionnaire($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci');
		
		return redirect()->route('admin.questionnaires.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $questionnaire = Questionnaire::find($id);
		$questionnaire->delete();
		
		$message = session()->flash('success', 'Anketa je uspješno obrisana');
		
		return redirect()->route('admin.questionnaires.index')->withFlashMessage($message);
    }
}
