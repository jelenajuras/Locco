<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EvaluatingQuestion;
use App\Models\EvaluatingGroup;
use App\Http\Requests\EvaluatingQuestionRequest;


class EvaluatingQuestionController extends Controller
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
        $evaluatingQuestions = EvaluatingQuestion::get();

		return view('admin.evaluating_questions.index',['evaluatingQuestions'=>$evaluatingQuestions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $evaluatingGroups = EvaluatingGroup::get();
		
		return view('admin.evaluating_questions.create',['evaluatingGroups'=>$evaluatingGroups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluatingQuestionRequest $request)
    {
        $input = $request;

		$data = array(
			'naziv'  	=> $input['naziv'],
			'opis'  	=> $input['opis'],
			'group_id'	=> $input['group_id']
		);
		
		$evaluatingQuestions = new EvaluatingQuestion();
		$evaluatingQuestions->saveEvaluatingQuestion($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova podkategorija');
		
		return redirect()->route('admin.evaluating_questions.index')->withFlashMessage($message);
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
        $evaluatingQuestion = EvaluatingQuestion::find($id);
		$evaluatingGroups = EvaluatingGroup::get();
		 
		return view('admin.evaluating_questions.edit',['evaluatingQuestion'=>$evaluatingQuestion, 'evaluatingGroups'=>$evaluatingGroups]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluatingQuestionRequest $request, $id)
    {
        $evaluatingQuestion = EvaluatingQuestion::find($id);
		$input = $request;
		
		$data = array(
			'naziv'  	=> $input['naziv'],
			'opis'  	=> $input['opis'],
			'group_id'	=> $input['group_id']
		);
		
		$evaluatingQuestion->updateEvaluatingQuestion($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.evaluating_questions.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluatingQuestion = EvaluatingQuestion::find($id);
		$evaluatingQuestion->delete();
		
		$message = session()->flash('success', 'Podkategrija je uspješno obrisana');
		
		return redirect()->route('admin.evaluating_questions.index')->withFlashMessage($message);
    }
}
