<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee_department extends Model
{
    protected $fillable = ['department_id','employee_id'];
	
	/*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 
	
	
	/*
	* Returns the projekt relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'department_id');
	}	
	
	/*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	
	/*
	* Returns the projekt relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}	
	
	
	/*
	* Save EmployeeDepartment
	* 
	* @param array $employeeDepartment
	* @return void
	*/
	
	public function saveEmployeeDepartment($employeeDepartment=array())
	{
		return $this->fill($employeeDepartment)->save();
	}
	
	/*
	* Update EmployeeDepartment
	* 
	* @param array $department
	* @return void
	*/
	
	public function updateEmployeeDepartment($employeeDepartment=array())
	{
		return $this->update($employeeDepartment);
	}	
}
