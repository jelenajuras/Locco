<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
	/* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['zahtjev','employee_id','GOpocetak','GOzavršetak','vrijeme_od','vrijeme_do','napomena','odobreno','odobreno2','razlog','odobrio_id','datum_odobrenja'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	
	
	/*
	* Returns the employees relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeesModel,'employee_id');
	}
	
	/*
	* Returns the authorized relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function authorized()
	{
		return $this->belongsTo(static::$employeesModel,'odobrio_id');
	}
	
	/*
	* Save VacationRequest
	* 
	* @param array $VacationRequest
	* @return void
	*/
	
	public function saveVacationRequest($VacationRequest=array())
	{
		return $this->fill($VacationRequest)->save();
	}
	
	/*
	* Update VacationRequest
	* 
	* @param array $VacationRequest
	* @return void
	*/
	
	public function updateVacationRequest($VacationRequest=array())
	{
		return $this->update($VacationRequest);
	}	
}
