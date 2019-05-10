<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['article','subject','theme_id','employee_id','status'];
	
	/*
	* The Eloquent educationTheme model name
	* 
	* @var string
	*/
	protected static $educationThemeModel = 'App\Models\EducationTheme'; 
	
	/*
	* Returns the educationTheme relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function educationTheme()
	{
		return $this->belongsTo(static::$educationThemeModel,'theme_id');
	}
	
	
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
	* Save Presentation
	* 
	* @param array $presentation
	* @return void
	*/
	public function savePresentation($presentation=array())
	{
		return $this->fill($presentation)->save();
	}
	
	/*
	* Update Presentation
	* 
	* @param array $presentation
	* @return void
	*/
	
	public function updatePresentation($presentation=array())
	{
		return $this->update($presentation);
	}	
}
