<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentInstruction extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','instruction_id','content'];
	
	/*
	* The Eloquent employee model names
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee';
	
	/*
	* The Eloquent instruction model name
	* 
	* @var string
	*/
	protected static $instructionModel = 'App\Models\Instruction'; 	
	
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
	* Returns the instruction  relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function instruction()
	{
		return $this->belongsTo(static::$instructionModel,'instruction_id');
	}	
	
	/*
	* Save CommentInstruction
	* 
	* @param array $comment
	* @return void
	*/
	
	public function saveCommentInstruction($comment=array())
	{
		return $this->fill($comment)->save();
	}
	
	/*
	* Update CommentInstruction
	* 
	* @param array $comment
	* @return void
	*/
	
	public function updateCommentInstruction($comment=array())
	{
		return $this->update($comment);
	}	
}
