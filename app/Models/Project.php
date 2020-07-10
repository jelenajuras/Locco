<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	
	protected $fillable = ['id','erp_id','investitor_id','customer_oib','customer_id','naziv','objekt','user_id','active'];
	
	/*
	* The Eloquent customer model name
	* 
	* @var string
	*/
	protected static $customerModel = 'App\Models\Customer'; 	
	
	/*
	* The Eloquent investitor model name
	* 
	* @var string
	*/
	protected static $investitorModel = 'App\Models\Customer'; 		
	
	/*
	* The Eloquent locco model name
	* 
	* @var string
	*/
	protected static $loccoModel = 'App\Models\Locco'; 
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 	
	
	/*
	* Returns the locco relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function locco()
	{
		return $this->hasMany(static::$loccoModel,'projekt_id')->orderBy('created_at','DESC')->paginate(10);
	}	
	
	/*
	* Returns the customer relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function narucitelj()
	{
		return $this->belongsTo(static::$customerModel,'customer_id');
	}
	
	/*
	* Returns the customer relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'user_id');
	}
	
	/*
	* Returns the investitor relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function investitor()
	{
		return $this->belongsTo(static::$investitorModel,'investitor_id');
	}
	
	/*
	* Save Project
	* 
	* @param array $project
	* @return void
	*/
	
	public function saveProject($project=array())
	{
		return $this->fill($project)->save();
	}
	
	/*
	* Update Project
	* 
	* @param array $project
	* @return void
	*/
	
	public function updateProject($project=array())
	{
		return $this->update($project);
	}
}