<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CatalogCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogCategoryRequest;
use App\Models\CatalogManufacturer;

class CatalogCategoryController extends Controller
{ 
    
    /**
    * Set middleware to quard controller.
    *
    * @return void
    */
    public function __construct()
     {
         $this->middleware('sentinel.auth');
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalog_categories = CatalogCategory::get();
        
        return view('admin.catalog_categories.index',['catalog_categories'=>$catalog_categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.catalog_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogCategoryRequest $request)
    {
        $data = array(
			'name'  	    => $request['name'],
			'description'  	=> $request['description'],
		);
		
		$catalog_category = new CatalogCategory();
		$catalog_category->saveCatalogCategory($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova kategorija');

		return redirect()->route('admin.catalog_categories.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $catalog_categories = CatalogCategory::get();
        $catalog_manufacturers = CatalogManufacturer::get();

        return view('admin.catalog_categories.show', ['catalog_categories' => $catalog_categories, 'catalog_manufacturers' => $catalog_manufacturers]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $catalog_category = CatalogCategory::find($id);

        return view('admin.catalog_categories.edit',['catalog_category' => $catalog_category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CatalogCategoryRequest $request, $id)
    {
        $catalog_category = CatalogCategory::find($id);

        $data = array(
			'name'  	    => $request['name'],
			'description'  	=> $request['description'],
		);
		
	
		$catalog_category->updateCatalogCategory($data);
		
		$message = session()->flash('success', 'Kategorija je ispravljena');

        return redirect()->route('admin.catalog_categories.index')->withFlashMessage($message);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $catalog_category = CatalogCategory::find($id);
        $catalog_category->delete();
        
        $message = session()->flash('success', 'Kategorija je uspješno obrisana');
        
        return redirect()->route('admin.catalog_categories.index')->withFlashMessage($message);
    }
}
