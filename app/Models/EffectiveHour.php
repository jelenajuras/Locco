<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EffectiveHour extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','effective_cost','brutto'];
	
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
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $registrationModel = 'App\Models\Registration'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function registration()
	{
		return $this->hasMany(static::$registrationModel,'employee_id');
	}
	
	/*
	* Save EffectiveHour
	* 
	* @param array $effectiveHour
	* @return void
	*/
	public function saveEffectiveHour($effectiveHour=array())
	{
		return $this->fill($effectiveHour)->save();
	}
	
	/*
	* Update EffectiveHour
	* 
	* @param array $effectiveHour
	* @return void
	*/
	
	public function updateEffectiveHour($effectiveHour=array())
	{
		return $this->update($effectiveHour);
	}	
}
