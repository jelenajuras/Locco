<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['name','description','institution'];
	
	/*
	* Save Training
	* 
	* @param array $training
	* @return void
	*/
	
	public function saveTraining($training=array())
	{
		return $this->fill($training)->save();
	}
	
	/*
	* Update Training
	* 
	* @param array $training
	* @return void
	*/
	
	public function updateTraining($training=array())
	{
		return $this->update($training);
	}	
}
