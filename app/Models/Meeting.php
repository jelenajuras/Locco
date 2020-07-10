<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['datum','start_time','end_time','employee_id','meeting_room_id','project_id','description'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent meetingRoom model name
	* 
	* @var string
	*/
	protected static $meetingRoomModel = 'App\Models\MeetingRoom'; 
	
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
	public function meetingRoom()
	{
		return $this->belongsTo(static::$meetingRoomModel,'meeting_room_id');
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
	* Save Meeting
	* 
	* @param array $meting
	* @return void
	*/
	public function saveMeeting($meting=array())
	{
		return $this->fill($meting)->save();
	}
	
	/*
	* Update Meeting
	* 
	* @param array $meting
	* @return void
	*/
	
	public function updateMeeting($meting=array())
	{
		return $this->update($meting);
	}	
}
