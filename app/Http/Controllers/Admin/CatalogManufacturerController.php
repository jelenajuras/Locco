<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CatalogManufacturer;
use App\Models\CatalogCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogManufacturerRequest;

class CatalogManufacturerController extends Controller
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
    public function index(Request $request)
    {
        $category = CatalogCategory::find($request['id']);
        $catalog_manufacturers = CatalogManufacturer::where('category_id', $request['id'])->get();
      
        return view('admin.catalog_manufacturers.index',['catalog_manufacturers'=>$catalog_manufacturers, 'category'=> $category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $category_id = $request['category_id'];
      
        return view('admin.catalog_manufacturers.create',['category_id' => $category_id ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogManufacturerRequest $request)
    {
        $data = array(
			'category_id'  	=> $request['category_id'],
			'name'  	    => $request['name'],
			'url'  	        => $request['url'],
			'email'  	    => $request['email'],
			'phone'  	    => $request['phone'],
		);
		
		$catalog_manufacturer = new CatalogManufacturer();
		$catalog_manufacturer->saveCatalogManufacturer($data);
		
		$message = session()->flash('success', 'Uspješno je dodan novi proizvođač');

        $category = CatalogCategory::find($request['category_id']);

        return redirect()->back()->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $catalog_manufacturer = CatalogManufacturer::find($id);
        $catalog_categories = CatalogCategory::get();

        return view('admin.catalog_manufacturers.edit',['catalog_manufacturer' => $catalog_manufacturer, 'catalog_categories' => $catalog_categories ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CatalogManufacturerRequest $request, $id)
    {
        $catalog_manufacturer = CatalogManufacturer::find($id);
      
        $data = array(
			'category_id'  	=> $request['category_id'],
			'name'  	    => $request['name'],
			'url'  	        => $request['url'],
			'email'  	    => $request['email'],
			'phone'  	    => $request['phone'],
        );

        $catalog_manufacturer->updateCatalogManufacturer($data);
        $category = CatalogCategory::find($catalog_manufacturer->category_id);

        $message = session()->flash('success', 'Podaci su ispravljeni');
        
        return redirect()->back()->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $catalog_manufacturer = CatalogManufacturer::find($id);
        $catalog_manufacturer->delete();
        
        $message = session()->flash('success', 'Proizvođač je uspješno obrisana');
        
        return redirect()->route('admin.catalog_categories.index')->withFlashMessage($message);
    }
}
