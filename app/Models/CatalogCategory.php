<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogCategory extends Model
{
    protected $fillable = ['name','description'];

    /*
	* Save CatalogCategory
	* 
	* @param array $category
	* @return void
	*/
	
	public function saveCatalogCategory ($category=array())
	{
		return $this->fill($category)->save();
	}
	
	/*
	* Update CatalogCategory
	* 
	* @param array $category
	* @return void
	*/
	
	public function updateCatalogCategory($category=array())
	{
		return $this->update($category);
	}	
}
