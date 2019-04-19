<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EducationRequest;
use App\Models\Education;
use App\Models\EducationTheme;
use App\Models\EducationArticle;
use App\Models\EducationComment;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Employee_department;
use Sentinel;

class EducationController extends Controller
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
        $educations = Education::get();
		$departments = Department::get();
		
		return view('admin.educations.index',['educations'=>$educations, 'departments'=>$departments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$departments0 = Department::where('level',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level',1)->orderBy('name','ASC')->get();
		$departments2 = Department::where('level',2)->orderBy('name','ASC')->get();
		$employee_departments = Employee_department::get();
		
		return view('admin.educations.create',['departments0'=>$departments0, 'departments1'=>$departments1, 'departments2'=>$departments2, 'employee_departments'=>$employee_departments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EducationRequest $request)
    {
		$input = $request;
		$to_department_id = implode(",", $input['to_department_id'] );
		
		$data = array(
			'name'  			=> $input['name'],
			'to_department_id'  => $to_department_id,
			'status'  	 		=> $input['status']
		);
			
		$education = new Education();
		$education->saveEducation($data);
		
		$message = session()->flash('success', 'Uspješno je dodana nova edukacija');
		
		return redirect()->route('admin.educations.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $education = Education::find($id);
     //    $educationThemes = EducationTheme::where('education_id', $education->id)->get();
         $educationArticles = EducationArticle::orderBy('created_at','DESC')->get();
         $educationComments = EducationComment::get();

		 return view('admin.educations.show', ['education' => $education, 'educationArticles' => $educationArticles, 'educationComments' => $educationComments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $education = Education::find($id);
	
		$departments0 = Department::where('level',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level',1)->orderBy('name','ASC')->get();
		$departments2 = Department::where('level',2)->orderBy('name','ASC')->get();
		$employee_departments = Employee_department::get();
	
		return view('admin.educations.edit', ['education' => $education,'departments0'=>$departments0, 'departments1'=>$departments1, 'departments2'=>$departments2, 'employee_departments'=>$employee_departments]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EducationRequest $request, $id)
    {
       $education = Education::find($id);
	   
		$input = $request->except(['_token']);
		$to_department_id = implode(",", $input['to_department_id'] );
		
		$data = array(
			'name'  	 => $input['name'],
			'to_department_id'  => $to_department_id,
			'status'  	 => $input['status']
		);
		
		$education->updateEducation($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.educations.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $education = Education::find($id);
		$education->delete();
		
		$message = session()->flash('success', 'Edukacija je obrisana.');
		
		return redirect()->route('admin.educations.index')->withFlashMessage($message);
    }
}
