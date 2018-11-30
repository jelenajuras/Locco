<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','radnoMjesto_id','datum_prijave','probni_rok','staz','lijecn_pregled','ZNR','napomena','slDani','prekidStaza','prvoZaposlenje'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent effectiveHour model name
	* 
	* @var string
	*/
	protected static $effectiveHourModel = 'App\Models\EffectiveHour'; 
	
	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 
	
	/*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function work()
	{
		return $this->belongsTo(static::$workModel,'radnoMjesto_id');
	}
	
	/*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function ech()
	{
		return $this->belongsTo(static::$effectiveHourModel,'employee_id');
	}
	
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
	* Save Registration
	* 
	* @param array $registration
	* @return void
	*/
	public function saveRegistration($registration=array())
	{
		return $this->fill($registration)->save();
	}
	
	/*
	* Update Registration
	* 
	* @param array $registration
	* @return void
	*/
	
	public function updateRegistration($registration=array())
	{
		return $this->update($registration);
	}	
}
