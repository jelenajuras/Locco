<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EvaluatingRating;
use App\Http\Requests\EvaluatingRatingRequest;

class EvaluatingRatingController extends Controller
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
        $evaluatingRatings = EvaluatingRating::get();

		return view('admin.evaluating_ratings.index',['evaluatingRatings'=>$evaluatingRatings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.evaluating_ratings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluatingRatingRequest $request)
    {
        $input = $request;

		$data = array(
			'naziv'  	=> $input['naziv'],
			'rating'  	=> $input['rating']
		);
		
		$evaluatingRating = new EvaluatingRating();
		$evaluatingRating->saveEvaluatingRating($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova ocjena');
		
		return redirect()->route('admin.evaluating_ratings.index')->withFlashMessage($message);
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
        $evaluatingRating = EvaluatingRating::find($id);
		 
		return view('admin.evaluating_ratings.edit',['evaluatingRating'=>$evaluatingRating]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluatingRatingRequest $request, $id)
    {
        $evaluatingRating = EvaluatingRating::find($id);
		$input = $request;
		
		$data = array(
			'naziv'  	=> $input['naziv'],
			'rating'  	=> $input['rating']
		);
		
		$evaluatingRating->updateEvaluatingRating($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.evaluating_ratings.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluatingRating = EvaluatingRating::find($id);
		$evaluatingRating->delete();
		
		$message = session()->flash('success', 'Ocjena je uspješno obrisano');
		
		return redirect()->route('admin.evaluating_ratings.index')->withFlashMessage($message);
    }
}
