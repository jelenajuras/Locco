<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdCategory;

class AdCategoryController extends Controller
{
    /**
   *
   * Set middleware to quard controller.
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
		$adCategories = AdCategory::get();

		return view('admin.ad_categories.index', ['adCategories'=>$adCategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ad_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token']);
		
		$data = array(
			'name'  		=> $input['name']
		);
		
		$adCategory = new AdCategory();
		$adCategory->saveCategory($data);
		
		$message = session()->flash('success', 'Kategorija je spremljena');
			
		//return redirect()->back()->withFlashMessage($message);
		return redirect()->route('admin.ad_categories.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $adCategory = AdCategory::find($id);

		return view('admin.ad_categories.edit', ['adCategory'=>$adCategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adCategory = AdCategory::find($id);
		$input = $request->except(['_token']);
		
		$data = array(
			'name'  		=> $input['name']
		);
		$adCategory->updateCategory($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
			
		return redirect()->route('admin.ad_categories.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adCategory = AdCategory::find($id);
		$adCategory->delete();
		
		$message = session()->flash('success', 'Kategorija je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
