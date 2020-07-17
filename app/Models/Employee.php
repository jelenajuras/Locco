<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['first_name','last_name','maiden_name','ime_oca','ime_majke','oib','oi','oi_istek','datum_rodjenja','mjesto_rodjenja','mobitel','email','priv_mobitel','priv_email','prebivaliste_adresa','prebivaliste_grad','boraviste_adresa','boraviste_grad','zvanje','sprema','bracno_stanje','radnoMjesto_id','lijecn_pregled','ZNR','konf_velicina','broj_cipela','napomena'];
	
	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $worksModel = 'App\Models\Work'; 
	
	/*
	* The Eloquent registration model name
	* 
	* @var string
	*/
	protected static $registrationModel = 'App\Models\Registration'; 

	/*
	* The Eloquent employeeDepartment model names
	* 
	* @var string
	*/
	protected static $EmployeeDepartmentModel = 'App\Models\Employee_department';
	
	/*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function work()
	{
		return $this->belongsTo(static::$worksModel,'radnoMjesto_id');
	}

	/*
	* Returns the registration relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasOne
	*/
	
	public function registration()
	{
		return $this->hasOne(static::$registrationModel,'employee_id');
	}

	/*
	* Returns the comments relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function departments()
	{
		return $this->hasMany(static::$EmployeeDepartmentModel,'employee_id');
	}	

	/*
	* Save Employee
	* 
	* @param array $employee
	* @return void
	*/
	
	public function saveEmployee($employee=array())
	{
		return $this->fill($employee)->save();
	}
	
	/*
	* Update Employee
	* 
	* @param array $employee
	* @return void
	*/
	
	public function updateEmployee($employee=array())
	{
		return $this->update($employee);
	}	
	
}
