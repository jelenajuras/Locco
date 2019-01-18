<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluatingEmployee extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluating_employees';
	
	 /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','ev_employee_id','mjesec_godina','questionnaire_id','status'];

	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent Questionnaire model name
	* 
	* @var string
	*/
	protected static $questionnaireModel = 'App\Models\Questionnaire'; 
	
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
	* Returns the Questionnaire relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function questionnaire()
	{
		return $this->belongsTo(static::$questionnaireModel,'questionnaire_id');
	}
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	public function evaleated_employee()
	{
		return $this->belongsTo(static::$employeeModel,'ev_employee_id');
	}

	/*
	* Save EvaluatingEmployee
	* 
	* @param array $evaluatingEmployee
	* @return void
	*/
	public function saveEvaluatingEmployee($evaluatingEmployee=array())
	{
		return $this->fill($evaluatingEmployee)->save();
	}
	
	/*
	* Update EvaluatingEmployee
	* 
	* @param array $evaluatingEmployee
	* @return void
	*/
	
	public function updateEvaluatingEmployee($evaluatingEmployee=array())
	{
		return $this->update($evaluatingEmployee);
	}	
	
}
