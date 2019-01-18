<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['naziv','adresa','grad','oib','active'];

	/*
	* The Eloquent projekt model name
	* 
	* @var string
	*/
	protected static $projektModel = 'App\Models\Project'; 
	
	
	/*
	* Returns the projekt relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function projekt()
	{
		return $this->hasMany(static::$projektModel,'customer_id')->orderBy('created_at','DESC')->paginate(10);
	}	
	
	/*
	* Save Customer
	* 
	* @param array $Customer
	* @return void
	*/
	
	public function saveCustomer($customer=array())
	{
		return $this->fill($customer)->save();
	}
	
	/*
	* Update Customer
	* 
	* @param array $Customer
	* @return void
	*/
	
	public function updateCustomer($customer=array())
	{
		return $this->update($customer);
	}	
}