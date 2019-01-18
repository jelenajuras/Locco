<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EvaluatingGroup;
use App\Models\EvaluatingQuestion;
use App\Models\Questionnaire;
use App\Http\Requests\EvaluatingGroupRequest;

class EvaluatingGroupController extends Controller
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
        $evaluatingGroups = EvaluatingGroup::get();
		
		return view('admin.evaluating_groups.index',['evaluatingGroups'=>$evaluatingGroups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questionnaires = Questionnaire::get();
		
		return view('admin.evaluating_groups.create',['questionnaires'=>$questionnaires]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluatingGroupRequest $request)
    {
		$input = $request;

		$data = array(
			'questionnaire_id'	=> $input['questionnaire_id'],
			'naziv'  	  		=> $input['naziv'],
			'koeficijent' 		=> str_replace(',','.', $input['koeficijent'])
		);
		
		$evaluatingGroup = new EvaluatingGroup();
		$evaluatingGroup->saveEvaluatingGroup($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova kategorija');
		
		return redirect()->route('admin.evaluating_groups.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
	   $evaluatingGroup = EvaluatingGroup::find($id);
	   $evaluatingQuestions = EvaluatingQuestion::where('group_id',$id)->get();
		
	   return view('admin.evaluating_groups.show',['evaluatingGroup'=>$evaluatingGroup, 'evaluatingQuestions'=>$evaluatingQuestions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $evaluatingGroup = EvaluatingGroup::find($id);
		$questionnaires = Questionnaire::get();
		
		return view('admin.evaluating_groups.edit',['evaluatingGroup'=>$evaluatingGroup, 'questionnaires'=>$questionnaires]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluatingGroupRequest $request, $id)
    {
		$evaluatingGroup = EvaluatingGroup::find($id);
		$input = $request;
		
		$data = array(
			'questionnaire_id'	=> $input['questionnaire_id'],
			'naziv'  	 		=> $input['naziv'],
			'koeficijent'		=> str_replace(',','.', $input['koeficijent'])
		);
		
		$evaluatingGroup->updateEvaluatingGroup($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.evaluating_groups.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluatingGroup = EvaluatingGroup::find($id);
		$evaluatingGroup->delete();
		
		$message = session()->flash('success', 'kategorija je uspješno obrisana');
		
		return redirect()->route('admin.evaluating_groups.index')->withFlashMessage($message);
    }
}
