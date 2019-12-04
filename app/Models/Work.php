<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $fillable = ['odjel_id','odjel','naziv','job_description','pravilnik','tocke','user_id','prvi_userId','drugi_userId'];
	
	/*
	* The Eloquent employees model names
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee';
	
	/*
	* The Eloquent employees model names
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department';
			
	/*
	* Returns the employee relationship
	* 	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'odjel_id');
	}	
		
	/*
	* Returns the employee relationship
	* 	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function nadredjeni()
	{
		return $this->belongsTo(static::$employeesModel,'user_id');
	}	
		
	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function prvi_nadredjeni()
	{
		return $this->belongsTo(static::$employeesModel,'prvi_userId');
	}
	
	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function drugi_nadredjeni()
	{
		return $this->belongsTo(static::$employeesModel,'drugi_userId');
	}
	
	/*
	* Save Work
	* 
	* @param array $work
	* @return void
	*/
	
	public function saveWork($work=array())
	{
		return $this->fill($work)->save();
	}
	
	/*
	* Update Work
	* 
	* @param array $work
	* @return void
	*/
	
	public function updateWork($work=array())
	{
		return $this->update($work);
	}	
}