<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluatingRating extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluating_ratings';
	
	 /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['rating','naziv'];
	
	/*
	* Save EvaluatingRating
	* 
	* @param array $evaluatingRating
	* @return void
	*/
	public function saveEvaluatingRating($evaluatingRating=array())
	{
		return $this->fill($evaluatingRating)->save();
	}
	
	/*
	* Update EvaluatingRating
	* 
	* @param array $evaluatingRating
	* @return void
	*/
	
	public function updateEvaluatingRating($evaluatingRating=array())
	{
		return $this->update($evaluatingRating);
	}	
	
	
}
