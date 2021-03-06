<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEquipment extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','datum_zaduzenja','datum_povrata','equipment_id','kolicina','napomena'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent equipment model name
	* 
	* @var string
	*/
	protected static $equipmentModel = 'App\Models\Equipment'; 
	
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
	* Returns the comments relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function equipment()
	{
		return $this->belongsTo(static::$equipmentModel,'equipment_id');
	}	
	
	/*
	* Save employeeEquipment
	* 
	* @param array $employeeEquipment
	* @return void
	*/
	
	public function saveEmployeeEquipment($employeeEquipment=array())
	{
		return $this->fill($employeeEquipment)->save();
	}
	
	/*
	* Update Equipment
	* 
	* @param array $employeeEquipment
	* @return void
	*/
	
	public function updateEmployeeEquipment($employeeEquipment=array())
	{
		return $this->update($employeeEquipment);
	}	
}
