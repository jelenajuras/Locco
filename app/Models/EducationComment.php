<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationComment extends Model
{
     /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['article_id','employee_id','comment'];
	
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
	* The Eloquent educationTheme model name
	* 
	* @var string
	*/
	protected static $educationArticleModel = 'App\Models\EducationArticle'; 
	
	/*
	* Returns the educationTheme relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function educationArticle()
	{
		return $this->belongsTo(static::$educationArticleModel,'article_id');
	}
	
	
	/*
	* Save EducationComment
	* 
	* @param array $educationComment
	* @return void
	*/
	public function saveEducationComment($educationComment=array())
	{
		return $this->fill($educationComment)->save();
	}
	
	/*
	* Update EducationComment
	* 
	* @param array $educationComment
	* @return void
	*/
	
	public function updateEducationComment($educationComment=array())
	{
		return $this->update($educationComment);
	}	
	
}
