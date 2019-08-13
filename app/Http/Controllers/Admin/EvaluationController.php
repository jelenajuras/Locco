<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\EvaluatingQuestion;
use App\Models\EvaluatingGroup;
use App\Models\EvaluatingEmployee;
use App\Models\Registration;
use App\Models\EvaluationTarget;
use App\Models\Questionnaire;
use App\Models\EvaluatingRating;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Sentinel;
use DateTime;

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
		$mjesec_godina = EvaluatingEmployee::select('mjesec_godina')->distinct()->get();
		$evaluatingEmployee = EvaluatingEmployee::get();
		$data = array('evaluations'=> $evaluations, 'evaluatingEmployee'=> $evaluatingEmployee, 'mjesec_godina'=> $mjesec_godina, 'registrations'=> $registrations, 'questionnaires'=> $questionnaires, 'evaluatingGroups'=> $evaluatingGroups);
		
	//	return response()->json(array('data'=> $data), 200);
		
		return view('admin.evaluations.index',['evaluatingEmployee'=>$evaluatingEmployee,'evaluations'=>$evaluations,'mjesec_godina'=>$mjesec_godina,'registrations'=>$registrations,'questionnaires'=>$questionnaires,'evaluatingGroups'=>$evaluatingGroups]);
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
		$datum = new DateTime('now');
		$mjesec_godina = date_format($datum,'Y-m');
		$emp = Employee::where('id', $request['employee_id'])->first(); //

		if(!$request['rating']) {
				$message = session()->flash('error', 'Ne može se spremiti anketa bez ocjena');
				return redirect()->back()->withFlashMessage($message);
		} else {
			$input = $request->except(['_token']);
		
			if($input['tip_ankete'] == 'podgrupa') {
				if(count($input['question_id']) != count($input['rating'])) {
					$message = session()->flash('error', 'Anketa nije potpuna, sva pitanja su obavezna. Ponoviti anketu i odgovori na sva pitanja');
					return redirect()->back()->withFlashMessage($message);
				} else {
					foreach($input['question_id'] as $key => $question){
						$evaluatingQuestion = EvaluatingQuestion::where('id',$question)->first();
						$group = EvaluatingGroup::where('id',$evaluatingQuestion->group_id)->first();
						
						foreach($input['rating'] as $key2 => $value2){
							if($key2 === $key){
								$rating = $value2;
								$data = array(
									'employee_id'  	=> $input['ev_employee_id'],
									'datum'     	=> $input['datum'],
									'group_id'	 	=> $group->id,
									'questionnaire_id' => $input['questionnaire_id'],
									'question_id'	=> $question,
									'koef'			=> $group->koeficijent,
									'rating'	 	=> $rating
								);
								
								if($input['ev_employee_id'] === $input['employee_id'] || $emp->work['naziv'] == 'Direktor poduzeća'){
									$data['user_id'] = $input['employee_id'];
								}

								$evaluation = new Evaluation();
								$evaluation->saveEvaluation($data);
							}
						}
					}
				}
			} elseif ($input['tip_ankete'] == 'grupa' ) {
				if(count($input['group_id']) != count($input['rating'])) {
					$message = session()->flash('error', 'Anketa nije potpuna, sva pitanja su obavezna. Ponoviti anketu i odgovori na sva pitanja');
					return redirect()->back()->withFlashMessage($message);
				} else {
					foreach($input['group_id'] as $key => $question){
						$evaluatingQuestions = EvaluatingQuestion::where('group_id',$question)->get();
						$group = EvaluatingGroup::where('id',$question)->first();
						
						foreach($input['rating'] as $key2 => $value2){
							if($key2 === $key){
								$rating = $value2;
								
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
									if($input['ev_employee_id'] === $input['employee_id'] || $emp->work['naziv'] == 'Direktor poduzeća'){
										$data['user_id'] = $input['employee_id'];
									}
									$evaluation = new Evaluation();
									$evaluation->saveEvaluation($data);
								}
							} 
						}
					}
				}
			}
			
			$data2 = array(
				'employee_id'  	 	=> $input['employee_id'],
				'ev_employee_id'  	=> $input['ev_employee_id'],
				'mjesec_godina'  	=> $mjesec_godina,
				'questionnaire_id'  => $input['questionnaire_id'],
				'status'  		=> "OK"
			);
			
			$evaluatingEmployee = new EvaluatingEmployee();
			$evaluatingEmployee->saveEvaluatingEmployee($data2);

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
		}
		//	return redirect()->route('home')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
		$employee = Registration::where('employee_id',$id)->first();
		
		$evaluations = Evaluation::where('employee_id',$id)->where('datum', 'LIKE' ,$request['mjesec_godina'].'%')->where('questionnaire_id',$request['questionnaire_id'])->get();
		$evaluatingEmployees = EvaluatingEmployee::where('ev_employee_id',$employee->employee_id)->get();
		
		$evaluation_D = $evaluatingEmployees->where('employee_id',58)->where('ev_employee_id',$employee->employee_id)->first(); // Direktor
		
		$questionnaire = Questionnaire::where('id', $request['questionnaire_id'])->first();
		$evaluatingGroups = EvaluatingGroup::where('questionnaire_id', $request['questionnaire_id'])->get();
		$evaluatingQuestions = EvaluatingQuestion::get();
		$mjesec_godina = $request['mjesec_godina'];
		$ratings = EvaluatingRating::get();
		$targets = EvaluationTarget::where('questionnaire_id',$questionnaire->id)->where('employee_id',$id)->where('mjesec_godina',$mjesec_godina)->get();
		
		return view('admin.evaluations.show', ['employee' => $employee, 'evaluation_D' => $evaluation_D, 'evaluations' => $evaluations,'questionnaire' => $questionnaire, 'evaluatingGroups' => $evaluatingGroups,  'evaluatingQuestions' => $evaluatingQuestions, 'mjesec_godina' =>$request['mjesec_godina'], 'evaluatingEmployees' => $evaluatingEmployees, 'questionnaire_id' => $request['questionnaire_id'], '$mjesec_godina' => $mjesec_godina, 'ratings' => $ratings, 'targets' => $targets ]);
		
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
		$evaluatingEmployee = EvaluatingEmployee::find($id);
		$evaluations = Evaluation::where('user_id',$evaluatingEmployee->employee_id)->where('employee_id',$evaluatingEmployee->ev_employee_id)->where('questionnaire_id',$evaluatingEmployee->questionnaire_id)->where('datum', 'LIKE' , $evaluatingEmployee->mjesec_godina.'%')->get();
		
		$user = Sentinel::getUser();
		$employee = Employee::where('first_name', $user->first_name)->where('last_name', $user->last_name)->first();
		
		$danas = new DateTime('now');
		$mjesec_godina = date_format($danas,'Y-m');

		
	    $questionnaire = Questionnaire::where('id', $evaluatingEmployee->questionnaire_id)->first();
		$evaluatingGroups = EvaluatingGroup::where('questionnaire_id', $questionnaire->id)->get();
		$evaluatingQuestion = EvaluatingQuestion::get();
		$evaluatingRatings = EvaluatingRating::get();

		return view('admin.evaluations.edit',['employee'=>$employee,'evaluations'=>$evaluations,'evaluatingEmployee'=>$evaluatingEmployee,'questionnaire'=>$questionnaire,'evaluatingGroups'=>$evaluatingGroups,'evaluatingQuestion'=>$evaluatingQuestion,'evaluatingRatings'=>$evaluatingRatings]);
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
	   $input = $request->except(['_token']);
	   $emp = Registration::where('employee_id', $request['employee_id'])->first();
	   $evaluatingEmployee = EvaluatingEmployee::find($id);
	   
	   $evaluations = Evaluation::where('user_id',$evaluatingEmployee->employee_id)->where('employee_id',$evaluatingEmployee->ev_employee_id)->where('questionnaire_id',$evaluatingEmployee->questionnaire_id)->where('datum', 'LIKE' , $evaluatingEmployee->mjesec_godina.'%')->get();
	   
	   foreach($evaluations as $evaluation){
			if($input['tip_ankete'] == 'podgrupa') {
				foreach($input['question_id'] as $key => $question){
					$evaluatingQuestion = EvaluatingQuestion::where('id',$question)->first();
					$group = EvaluatingGroup::where('id',$evaluatingQuestion->group_id)->first();
					
					foreach($input['rating'] as $key2 => $value2){
						if($key2 === $key){
							$rating = $value2;
							if($evaluation->group_id == $group->id && $evaluation->question_id == $evaluatingQuestion->id){
								$data = array(
									'employee_id'  	=> $input['ev_employee_id'],
									'datum'     	=> $input['datum'],
									'group_id'	 	=> $group->id,
									'questionnaire_id' => $input['questionnaire_id'],
									'question_id'	=> $question,
									'koef'			=> $group->koeficijent,
									'rating'	 	=> $rating
								);
								if($input['ev_employee_id'] === $input['employee_id'] || $emp->work['naziv'] == 'Direktor poduzeća'){   //'Direktor poduzeća'
									$data['user_id'] = $input['employee_id'];
								}
								$evaluation->updateEvaluation($data);
							}
						}
					}
				}
			} elseif ($input['tip_ankete'] == 'grupa' ) {
				foreach($input['group_id'] as $key => $question){
					$evaluatingQuestions = EvaluatingQuestion::where('group_id',$question)->get();
					$group = EvaluatingGroup::where('id',$question)->first();
					
					foreach($input['rating'] as $key2 => $value2){
						if($key2 === $key){
							$rating = $value2;

							foreach($evaluatingQuestions as $evaluatingQuestion) {
								if($evaluation->group_id == $group->id && $evaluation->question_id == $evaluatingQuestion->id){
									$data = array(
										'employee_id'  	=> $input['ev_employee_id'],
										'datum'     	=> $input['datum'],
										'group_id'	 	=> $question,
										'questionnaire_id' => $input['questionnaire_id'],
										'question_id'	=> $evaluatingQuestion->id,
										'koef'			=> $group->koeficijent,
										'rating'	 	=> $rating
									);
									if($input['ev_employee_id'] === $input['employee_id'] || $emp->work['naziv'] == 'Direktor poduzeća'){    //'Direktor poduzeća'
										$data['user_id'] = $input['employee_id'];
									}
									$evaluation->updateEvaluation($data);
								}
							}
						}
					} 
				}
			}
	   }
		
		$employee = Registration::where('employee_id',$request['ev_employee_id'])->first();
		
		$evaluations = Evaluation::where('employee_id',$request['ev_employee_id'])->where('datum', 'LIKE', $evaluatingEmployee->mjesec_godina.'%')->where('questionnaire_id',$request['questionnaire_id'])->get();
		$evaluatingEmployees = EvaluatingEmployee::where('ev_employee_id',$employee->employee_id)->get();
		
		$evaluation_D = $evaluatingEmployees->where('employee_id',58)->where('ev_employee_id',$employee->employee_id)->first(); // Direktor 58
		
		$questionnaire = Questionnaire::where('id', $request['questionnaire_id'])->first();
		$evaluatingGroups = EvaluatingGroup::where('questionnaire_id', $request['questionnaire_id'])->get();
		$evaluatingQuestions = EvaluatingQuestion::get();
		
		return view('admin.evaluations.show', ['employee' => $employee, 'evaluation_D' => $evaluation_D, 'evaluations' => $evaluations,'questionnaire' => $questionnaire, 'evaluatingGroups' => $evaluatingGroups,  'evaluatingQuestions' => $evaluatingQuestions, 'mjesec_godina' => $evaluatingEmployee->mjesec_godina, 'evaluatingEmployees' => $evaluatingEmployees, 'questionnaire_id' => $request['questionnaire_idquestionnaire_id']]);
		
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
