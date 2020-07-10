<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Working_tag;
use App\Http\Requests\Working_tagRequest;
use App\Http\Controllers\Controller;
use Sentinel;

class Working_tagController extends Controller
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
        $workingTags = Working_tag::get();
		
		return view('admin.workingTags.index',['workingTags'=>$workingTags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.workingTags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Working_tagRequest $request)
    {
        $input = $request->except(['_token']);

		$data = array(
			'naziv'  => $input['naziv'],
			'sati'  => $input['sati']
		);
		
		$workingTag = new Working_tag();
		$workingTag->saveWorkingTag($data);
		
		$message = session()->flash('success', 'Dodano je novo radno vrijeme');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.workingTags.index')->withFlashMessage($message);
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
        $workingTag = Working_tag::find($id);
		
		return view('admin.workingTags.edit', ['workingTag' => $workingTag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Working_tagRequest $request, $id)
    {
        $workingTag = Working_tag::find($id);
		$input = $request->except(['_token']);

		$data = array(
			'naziv'  => $input['naziv'],
			'sati'  => $input['sati']
		);
		
		$workingTag->updateWorkingTag($data);
		
		$message = session()->flash('success', 'Radno vrijeme je ispravljeno');
		
		return redirect()->route('admin.workingTags.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $workingTag = Working_tag::find($id);
		$workingTag->delete();
		
		$message = session()->flash('success', 'Radno vrijeme je obrisano.');
		
		return redirect()->route('admin.workingTags.index')->withFlashMessage($message);
    }
}
