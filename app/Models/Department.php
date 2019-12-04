<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name','email','level','level1','employee_id'];

	/*
	* The Eloquent employees model names
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee';
	
	/*
	* Returns the employee relationship
	* 	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeesModel,'employee_id');
	}

	/*
	* Save Department
	* 
	* @param array $department
	* @return void
	*/
	
	public function saveDepartment($department=array())
	{
		return $this->fill($department)->save();
	}
	
	
	/*
	* Update Department
	* 
	* @param array $department
	* @return void
	*/
	
	public function updateDepartment($department=array())
	{
		return $this->update($department);
	}	
}
