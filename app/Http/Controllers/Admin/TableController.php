<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Table;
class TableController extends Controller
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
        $tables = Table::orderBy('name','ASC')->get();
				
		return view('admin.tables.index',['tables'=>$tables]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$input = $request;
		
		$data = array(
			'name'  	=> $input['name'],
			'description'  	=> $input['description']
		);
		
		$table = new Table();
		$table->saveTable($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova tablica');
		
		return redirect()->route('admin.tables.index')->withFlashMessage($message);
	
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
        $tbl = Table::find($id);

		return view('admin.tables.edit',['tbl'=>$tbl]);
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
         $tbl = Table::find($id);
		 
		 $input = $request;
		
		 $data = array(
			'name'  	=> $input['name'],
			'description'  	=> $input['description']
		);
		
		$tbl->updateTable($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci');
		return view('admin.tables.index')->withFlashMessage($message);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
