<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Http\Requests\BenefitRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class BenefitController extends Controller
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
        $benefits = Benefit::get();
		
		return view('admin.benefits.index',['benefits'=>$benefits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.benefits.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	
		if(empty ($request['comment'])) {
			$message = session()->flash('error', 'Nemoguće spremiti pogodnost bez teksta.');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
		$input = $request->except(['_token']);
		
		$data = array(
			'name'  		=> $input['name'],
			'description'   => $input['description'],
			'comment'  		=> $input['comment'],
			'url'  			=> $input['url'],
			'url2'  			=> $input['url2'],
			'status' 		=> $input['status']
		);
		
		$benefit = new Benefit();
		$benefit->saveBenefit($data);
		
		// snimanje dokumenta
		
		$target_dir = "storage/benefits/";
	
		$message = session()->flash('success', 'Nova pogodnost je upisana');
			
		//return redirect()->back()->withFlashMessage($message);
		return redirect()->route('admin.benefits.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $benefits = Benefit::where('status','aktivna')->get();
		
		return view('admin.benefits.show', ['benefits' => $benefits ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $benefit = Benefit::find($id);

		return view('admin.benefits.edit', ['benefit' => $benefit]);
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
        if(empty ($request['comment'])) {
			$message = session()->flash('error', 'Nemoguće spremiti pogodnost bez teksta.');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
		$benefit = Benefit::find($id);
		$input = $request->except(['_token']);
		
		$data = array(
			'name'  		=> $input['name'],
			'description'   => $input['description'],
			'comment'  		=> $input['comment'],
			'url'  			=> $input['url'],
			'url2'  			=> $input['url2'],
			'status' 		=> $input['status']
		);
		
		$benefit->updateBenefit($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
			
		return redirect()->route('admin.benefits.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $benefit = Benefit::find($id);
		$benefit->delete();
		
		$message = session()->flash('success', 'Pogodnost je obrisana.');
		
		return redirect()->route('admin.benefits.index')->withFlashMessage($message);
    }
	
	
}
