<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryEmployee extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['first_name','last_name','radnoMjesto_id','superior_id','datum_prijave','odjava','napomena','ime_oca','ime_majke','oib','oi','oi_istek','datum_rodjenja','mjesto_rodjenja','mobitel','email','priv_mobitel','priv_email','prebivaliste_adresa','prebivaliste_grad','zvanje','sprema','bracno_stanje','konf_velicina','broj_cipela'];
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
    
	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 
    
    
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'superior_id');
    }

    /*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function work()
	{
		return $this->belongsTo(static::$workModel,'radnoMjesto_id');
    }
    
    /*
	* Save TemporaryEmployee
	* 
	* @param array $temporaryEmployee
	* @return void
	*/
	public function saveTemporaryEmployee($temporaryEmployee=array())
	{
		return $this->fill($temporaryEmployee)->save();
	}
	
	/*
	* Update TemporaryEmployee
	* 
	* @param array $temporaryEmployee
	* @return void
	*/
	
	public function updateTemporaryEmployee($temporaryEmployee=array())
	{
		return $this->update($temporaryEmployee);
	}	

}
