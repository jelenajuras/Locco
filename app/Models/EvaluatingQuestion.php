<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluatingQuestion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluating_questions';
	
	 /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	
	protected $fillable = ['group_id','naziv','opis'];
	
	/*
	* The Eloquent group model name
	* 
	* @var string
	*/
	protected static $groupModel = 'App\Models\EvaluatingGroup'; 
	
	/*
	* Returns the group relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function group()
	{
		return $this->belongsTo(static::$groupModel,'group_id');
	}
	
	/*
	* Save EvaluatingQuestion
	* 
	* @param array $evaluatingQuestion
	* @return void
	*/
	public function saveEvaluatingQuestion($evaluatingQuestion=array())
	{
		return $this->fill($evaluatingQuestion)->save();
	}
	
	/*
	* Update EvaluatingQuestion
	* 
	* @param array $evaluatingQuestion
	* @return void
	*/
	
	public function updateEvaluatingQuestion($evaluatingQuestion=array())
	{
		return $this->update($evaluatingQuestion);
	}	
}
