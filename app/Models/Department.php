<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name','email','level','level1'];
	
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
