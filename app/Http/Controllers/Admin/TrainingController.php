<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training;

class TrainingController extends Controller
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
        $trainings = Training::orderBy('name','ASC')->get();
		
		return view('admin.trainings.index',['trainings'=>$trainings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.trainings.create');
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
			'name'  		=> $input['name'],
			'description'  	=> $input['description'],
			'institution'  	=> $input['institution']
		);
		
		$training = new Training();
		$training->saveTraining($data);
		
		$message = session()->flash('success', 'Novo osposobljavanje je spremljeno');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.trainings.index')->withFlashMessage($message);
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
        $training = Training::find($id);
		
		return view('admin.trainings.edit', ['training' => $training]);
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
        $training = Training::find($id);
		$input = $request->except(['_token']);

		$data = array(
			'name'  		=> $input['name'],
			'description'  	=> $input['description'],
			'institution'  	=> $input['institution']
		);
		
		$training->updateTraining($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.trainings.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $training = Training::find($id);
		$training->delete();
		
		$message = session()->flash('success', 'Osposobljavanje je obrisano.');
		
		return redirect()->route('admin.trainings.index')->withFlashMessage($message);
    }
}
