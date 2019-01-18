<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\EvaluatingQuestion;
use App\Models\EvaluatingGroup;
use App\Models\EvaluatingEmployee;
use App\Models\Registration;
use App\Models\Questionnaire;
use App\Models\EvaluatingRating;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Sentinel;

class EvaluationController extends Controller
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
		$evaluations = Evaluation::get();
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		$questionnaires = Questionnaire::get();
		$evaluatingGroups = EvaluatingGroup::get();
		
        return view('admin.evaluations.index',['evaluations'=>$evaluations,'registrations'=>$registrations,'questionnaires'=>$questionnaires,'evaluatingGroups'=>$evaluatingGroups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
		
		if($input['ev_employee_id'] === $input['employee_id'] || $input['tip_ankete'] == 'podgrupa' ) {
			foreach($input['question_id'] as $key => $question){
				$evaluatingQuestion = EvaluatingQuestion::where('id',$question)->first();
				$group = EvaluatingGroup::where('id',$evaluatingQuestion->group_id)->first();
				
				foreach($input['rating'] as $key2 => $value2){
					if($key2 === $key){
						$rating = $value2;
					}
				}
				
				$data = array(
					'employee_id'  	=> $input['ev_employee_id'],
					'datum'     	=> $input['datum'],
					'group_id'	 	=> $group->id,
					'questionnaire_id' => $input['questionnaire_id'],
					'question_id'	=> $question,
					'koef'			=> $group->koeficijent,
					'rating'	 	=> $rating
				);
				$data2 = array(
					'status'  		=> "OK",
				);
				$evaluation = new Evaluation();
				$evaluation->saveEvaluation($data);
			} 
		} else {
			foreach($input['group_id'] as $key => $question){
				$evaluatingQuestions = EvaluatingQuestion::where('group_id',$question)->get();
				$group = EvaluatingGroup::where('id',$question)->first();
				
				foreach($input['rating'] as $key2 => $value2){
					if($key2 === $key){
						$rating = $value2;
					}
				}
				foreach($evaluatingQuestions as $evaluatingQuestion) {
					$data = array(
						'employee_id'  	=> $input['ev_employee_id'],
						'datum'     	=> $input['datum'],
						'group_id'	 	=> $question,
						'questionnaire_id' => $input['questionnaire_id'],
						'question_id'	=> $evaluatingQuestion->id,
						'koef'			=> $group->koeficijent,
						'rating'	 	=> $rating
					);
					$evaluation = new Evaluation();
					$evaluation->saveEvaluation($data);
				}
				
				$data2 = array(
					'status'  		=> "OK",
				);
			} 
		}

		$evaluatingEmployee = EvaluatingEmployee::where('employee_id',$input['employee_id'])->where('ev_employee_id',$input['ev_employee_id'])->where('questionnaire_id',$input['questionnaire_id'])->first();
		
		$evaluatingEmployee->updateEvaluatingEmployee($data2);

		$message = session()->flash('success', 'Anketa je snimljena');
		
		$user = Sentinel::getUser();
		
		$employee = Employee::where('first_name', $user->first_name)->where('last_name', $user->last_name)->first();
		$employees = Employee::get();
	    $questionnaire = Questionnaire::find($input['questionnaire_id']);
		$evaluatingGroups = EvaluatingGroup::where('questionnaire_id', $questionnaire->id)->get();
		$evaluatingQuestion = EvaluatingQuestion::get();
		$evaluatingRatings = EvaluatingRating::get();
		$evaluatingEmployees = EvaluatingEmployee::where('employee_id', $employee->id)->where('status', null)->get();
		
		return redirect()->route('admin.questionnaires.show',['employee'=>$employee,'employees'=>$employees,'evaluatingEmployees'=>$evaluatingEmployees,'questionnaire'=>$questionnaire,'evaluatingGroups'=>$evaluatingGroups,'evaluatingQuestion'=>$evaluatingQuestion,'evaluatingRatings'=>$evaluatingRatings])->withFlashMessage($message);
		
	//	return redirect()->route('home')->withFlashMessage($message);
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
