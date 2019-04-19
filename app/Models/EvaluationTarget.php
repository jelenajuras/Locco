<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationTarget extends Model
{
    protected $fillable = ['employee_id','questionnaire_id','group_id','question_id','mjesec_godina','result','target','comment','comment_uprava'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
	
	/*
	* The Eloquent evaluatingQuestion model name
	* 
	* @var string
	*/
	protected static $eval_questionModel = 'App\Models\EvaluatingQuestion'; 
	
	/*
	* Returns the evaluatingQuestion relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function evaluatingQuestion()
	{
		return $this->belongsTo(static::$eval_questionModel,'question_id');
	}
	
	/*
	* The Eloquent questionnaire model name
	* 
	* @var string
	*/
	protected static $questionnaireModel = 'App\Models\Questionnaire'; 
	
	/*
	* Returns the questionnaire relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function questionnaire()
	{
		return $this->belongsTo(static::$questionnaireModel,'questionnaire_id');
	}
	
	
	/*
	* Save EvaluationTarget
	* 
	* @param array $evaluationTarget
	* @return void
	*/
	
	public function saveEvaluationTarget ($evaluationTarget=array())
	{
		return $this->fill($evaluationTarget)->save();
	}
	
	/*
	* Update EvaluationTarget
	* 
	* @param array $evaluationTarget
	* @return void
	*/
	
	public function updateEvaluationTarget($evaluationTarget=array())
	{
		return $this->update($evaluationTarget);
	}	

	
}

