<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluatingGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluating_groups';
	
	 /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	
	protected $fillable = ['naziv','koeficijent','questionnaire_id'];
	
	/*
	* The Eloquent group model name
	* 
	* @var string
	*/
	protected static $questionnaireModel = 'App\Models\Questionnaire'; 
	
	/*
	* Returns the group relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function questionnaire()
	{
		return $this->belongsTo(static::$questionnaireModel,'questionnaire_id');
	}
	
	/*
	* Save EvaluatingGroup
	* 
	* @param array $evaluatingGroup
	* @return void
	*/
	public function saveEvaluatingGroup($evaluatingGroup=array())
	{
		return $this->fill($evaluatingGroup)->save();
	}
	
	/*
	* Update EvaluatingGroup
	* 
	* @param array $evaluatingGroup
	* @return void
	*/
	
	public function updateEvaluatingGroup($evaluatingGroup=array())
	{
		return $this->update($evaluatingGroup);
	}	
	
}
