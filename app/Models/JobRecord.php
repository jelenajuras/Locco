<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRecord extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','date','task','odjel', 'time','task_manager'];
	
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
	* Returns the task_manager relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function manager()
	{
		return $this->belongsTo(static::$employeeModel,'task_manager');
	}
	
	/*
	* Save JobRecord
	* 
	* @param array $jobRecord
	* @return void
	*/
	public function saveJobRecord($jobRecord=array())
	{
		return $this->fill($jobRecord)->save();
	}
	
	/*
	* Update JobRecord
	* 
	* @param array $jobRecord
	* @return void
	*/
	
	public function updateJobRecord($jobRecord=array())
	{
		return $this->update($jobRecord);
	}	
}
