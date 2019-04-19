<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EducationThemeRequest;
use App\Models\EducationTheme;
use App\Models\Education;

class EducationThemeController extends Controller
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
		 if($request->id){
			 $educationThemes = EducationTheme::where('education_id',$request->id )->get();
		 } else {
			 $educationThemes = EducationTheme::get();
		 }
	 
		return view('admin.education_themes.index',['educationThemes'=>$educationThemes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $educations = Education::get();
		
		return view('admin.education_themes.create',['educations'=>$educations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EducationThemeRequest $request)
    {
		$input = $request;

		$data = array(
			'name'  	 	=> $input['name'],
			'education_id'  => $input['education_id']
		);
			
		$educationTheme = new EducationTheme();
		$educationTheme->saveEducationTheme($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova tema');
		
		return redirect()->route('admin.education_themes.index')->withFlashMessage($message);
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
        $educationTheme = EducationTheme::find($id);
		$educations = Education::get();
		
		return view('admin.education_themes.edit', ['educationTheme' => $educationTheme, 'educations'=>$educations]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EducationThemeRequest $request, $id)
    {
        $educationTheme = EducationTheme::find($id);
	   
		$input = $request->except(['_token']);
		
		$data = array(
			'name'  	 	=> $input['name'],
			'education_id'  => $input['education_id']
		);
		
		$educationTheme->updateEducationTheme($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.education_themes.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $educationTheme = EducationTheme::find($id);
		$educationTheme->delete();
		
		$message = session()->flash('success', 'Tema je obrisana.');
		
		return redirect()->route('admin.education_themes.index')->withFlashMessage($message);
    }
}
