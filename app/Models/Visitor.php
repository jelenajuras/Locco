<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['first_name','last_name','email','company','accept','confirm','card_id','returned'];
    
	/*
	* Save Visitor
	* 
	* @param array $visitor
	* @return void
	*/
	
	public function saveVisitor($visitor=array())
	{
		return $this->fill($visitor)->save();
	}
	
	/*
	* Update Visitor
	* 
	* @param array $visitor
	* @return void
	*/
	
	public function updateVisitor($visitor=array())
	{
		return $this->update($visitor);
	}	
}