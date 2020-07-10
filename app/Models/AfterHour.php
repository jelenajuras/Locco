<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AfterHour extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','project_id','datum','start_time', 'end_time', 'napomena','odobreno','odobrio_id','datum_odobrenja','odobreno_h'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 

		/*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $projectModel = 'App\Models\Project'; 
	
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
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function project()
	{
		return $this->belongsTo(static::$projectModel,'project_id');
	}
	

	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function odobrio()
	{
		return $this->belongsTo(static::$employeeModel,'odobrio_id');
	}
	
	/*
	* Save AfterHour
	* 
	* @param array $afterHour
	* @return void
	*/
	public function saveAfterHour($afterHour=array())
	{
		return $this->fill($afterHour)->save();
	}
	
	/*
	* Update AfterHour
	* 
	* @param array $afterHour
	* @return void
	*/
	
	public function updateAfterHour($afterHour=array())
	{
		return $this->update($afterHour);
	}	
}
