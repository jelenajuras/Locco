<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','to_employee_id','task','start_date','end_date','interval','active'];

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
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function toEmployee()
	{
		return $this->belongsTo(static::$employeeModel,'to_employee_id');
    }

	/*
	* Save Task
	* 
	* @param array $task
	* @return void
	*/
	public function saveTask($task=array())
	{
		return $this->fill($task)->save();
	}
	
	/*
	* Update Task
	* 
	* @param array $task
	* @return void
	*/
	
	public function updateTask($task=array())
	{
		return $this->update($task);
	}	
}
