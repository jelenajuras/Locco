<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryEmployeeRequest extends Model
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
	* Save TemporaryEmployeeRequest
	* 
	* @param array $VacationRequest
	* @return void
	*/
	
	public function saveTemporaryEmployeeRequest($TemporaryEmployeeRequest=array())
	{
		return $this->fill($TemporaryEmployeeRequest)->save();
	}
	
	/*
	* Update TemporaryEmployeeRequest
	* 
	* @param array $TemporaryEmployeeRequest
	* @return void
	*/
	
	public function updateTemporaryEmployeeRequest($TemporaryEmployeeRequest=array())
	{
		return $this->update($TemporaryEmployeeRequest);
	}
}
