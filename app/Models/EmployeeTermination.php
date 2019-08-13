<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTermination extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','otkaz_id','otkazni_rok','datum_odjave','napomena'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 

	/*
	* The Eloquent Work model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 
	
	/*
	* The Eloquent termination model name
	* 
	* @var string
	*/
	protected static $terminationModel = 'App\Models\Termination'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}	
	
	/*
	* Returns the termination relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function termination()
	{
		return $this->belongsTo(static::$terminationModel,'otkaz_id');
	}	
	
	/*
	* Returns the work relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function work()
	{
		return $this->belongsTo(static::$workModel,'radnoMjesto_id');
	}	
	
	/*
	* Save EmployeeTermination
	* 
	* @param array $employee_termination
	* @return void
	*/
	
	public function saveEmployeeTermination($employee_termination=array())
	{
		return $this->fill($employee_termination)->save();
	}
	
	/*
	* Update EmployeeTermination
	* 
	* @param array $employee_termination
	* @return void
	*/
	
	public function updateEmployeeTermination($employee_termination=array())
	{
		return $this->update($employee_termination);
	}	
}
