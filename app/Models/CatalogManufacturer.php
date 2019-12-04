<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogManufacturer extends Model
{
    protected $fillable = ['category_id','name','description','url','email','phone'];


    /*
	* The Eloquent category model name
	* 
	* @var string
	*/
	protected static $categoryModel = 'App\Models\CatalogCategory'; 
	
	/*
	* Returns the category relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function category()
	{
		return $this->belongsTo(static::$categoryModel,'category_id');
    }
    
    /*
	* Save CatalogManufacturer
	* 
	* @param array $manufacturer
	* @return void
	*/
	
	public function saveCatalogManufacturer ($manufacturer=array())
	{
		return $this->fill($manufacturer)->save();
	}
	
	/*
	* Update CatalogManufacturer
	* 
	* @param array $manufacturer
	* @return void
	*/
	
	public function updateCatalogManufacturer($manufacturer=array())
	{
		return $this->update($manufacturer);
	}	
}
