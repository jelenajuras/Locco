<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EvaluationTarget;
use App\Models\EvaluatingQuestion;
use App\Models\EvaluatingGroup;
use App\Models\Evaluation;
use App\Http\Requests\EvaluationTargetRequest;

class EvaluationTargetController extends Controller
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
        //
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
		
		foreach ($input['question_id'] as $key1 => $question){
			$evaluatingQuestion = EvaluatingQuestion::where('id',$question)->first();
			$group = EvaluatingGroup::where('id',$evaluatingQuestion->group_id)->first();
			
			foreach ($input['rating'] as $key5 => $rating){
				if($key1 === $key5){
					if($rating != 0){
						$data = array(
							'employee_id'  	=> $input['employee_id'],
							'datum'     	=> $input['datum'],
							'group_id'	 	=> $group->id,
							'questionnaire_id' => $input['questionnaire_id'],
							'question_id'	=> $question,
							'koef'			=> $group->koeficijent,
							'rating'	 	=> $rating
						);
						
						if($input['employee_id'] === $input['employee_id'] || $emp->work['naziv'] == 'Direktor poduzeća'){
							$data['user_id'] = $input['user_id'];
						}
						
						$evaluation = Evaluation::where('user_id', $input['user_id'])->where('employee_id',$input['employee_id'])->where('questionnaire_id',$input['questionnaire_id'])->where('datum', 'LIKE' , $input['mjesec_godina'].'%')->where('question_id',$question)->first();
						
						
						if($evaluation){
							$evaluation->updateEvaluation($data);
						} else {
							$evaluation = new Evaluation();
							$evaluation->saveEvaluation($data);
						}
						
					}
				}
			}
		}
		foreach ($input['group_id'] as $key1 => $group){
			foreach ($input['target'] as $key2 => $target){
				foreach ($input['comment'] as $key3 => $comment){
					if($key1 == $key2 && $key3 == $key1){
						$comment1 = $comment;
					}
					foreach ($input['result'] as $key4 => $result){
						if($key1 === $key2 && $key1 === $key4){
							if($target != 0 && $comment != '') {
								$data = array(
									'employee_id'  		=> $input['employee_id'],
									'questionnaire_id'  => $input['questionnaire_id'],
									'mjesec_godina' 	=> $input['mjesec_godina'],
									'group_id'	 	=> $group,
									'target'			=> $target,
									'result'			=> $result,
									'comment'	 		=> $comment1
								);
								$targets = EvaluationTarget::where('questionnaire_id',$input['questionnaire_id'])->where('employee_id',$input['employee_id'])->where('mjesec_godina',$input['mjesec_godina'])->where('group_id',$group)->first(); 
								if($targets) {
									$targets->updateEvaluationTarget($data);
								} else {
									$evaluationTarget = new EvaluationTarget();
									$evaluationTarget->saveEvaluationTarget($data);
								}
							}
						}
					}
				}
			}
		}
		if($input['comment_uprava'] != '') {
			$data = array(
				'employee_id'  		=> $input['employee_id'],
				'questionnaire_id'  => $input['questionnaire_id'],
				'mjesec_godina' 	=> $input['mjesec_godina'],
				'comment_uprava'	=> $input['comment_uprava']
			);
			$evaluationTarget = new EvaluationTarget();
			$evaluationTarget->saveEvaluationTarget($data);
		}
	
		
		$message = session()->flash('success', 'Ciljevi su upisani.');

		return redirect()->back()->withFlashMessage($message);
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
