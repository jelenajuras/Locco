<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PresentationRequest;
use App\Models\Presentation;
use App\Models\EducationTheme;
use App\Models\Education;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\Department;
use Sentinel;
use Mail;

class PresentationController extends Controller
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
			 $presentations = Presentation::where('theme_id',$request->id )->get();
		 } else {
			 $presentations = Presentation::get();
		 }
        
		$educations = Education::get();
		$educationTheme = EducationTheme::get();
		
		return view('admin.presentations.index',['presentations' => $presentations,'educations' => $educations,'educationTheme' => $educationTheme]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $educationThemes = EducationTheme::get();
		$registrations = Registration::get();
		
		return view('admin.presentations.create',['educationThemes' => $educationThemes, 'registrations' => $registrations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

		if(empty ($request['article'])) {
			$message = session()->flash('error', 'Nemoguće spremiti članak bez teksta.');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
		$user1 = Sentinel::getUser();
		$employee = Employee::where('first_name',$user1->first_name)->where('last_name',$user1->last_name)->first();
		
		$article = $request['article'];
		$dom = new \DomDocument();
		$dom->loadHtml(mb_convert_encoding($article, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		$images = $dom->getElementsByTagName('img');
		
		foreach($images as $k => $img){
			$data = $img->getAttribute('src');
			$dataFilename = $img->getAttribute('data-filename');
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$image_name= "/img/article/" . $dataFilename;
			$path = public_path() .  $image_name;
			file_put_contents($path, $data);
			$img->removeAttribute('src');
			$img->setAttribute('src', $image_name);
		}
			
		$article = $dom->saveHTML();

		$input = $request->except(['_token']);
		
	
		$data1 = array(
			'employee_id'   => $employee->id,
			'subject'   	=> $input['subject'],
			'theme_id'		=> $input['theme_id'],
			'article'  		=> $article, 
			'status'  		=> $input['status']
		);
		
		$presentation = new Presentation();
		$presentation->savePresentation($data1);
		
		$educationTheme = EducationTheme::where('id',$request['theme_id'])->first();
		$educations = Education::where('id',$educationTheme->education_id)->first();
		$departments = Department::get();
		if($educations->status == 'aktivna' && $presentation->status == 'aktivan') {
			$odjeli_id = explode(",",$educations->to_department_id);
			
			$odjel_email = array();
		//	$odjel_email = 'jelena.juras@duplico.hr';
			foreach($odjeli_id as $odjel_id){
				$department_mail = $departments->where('id',$odjel_id)->first();
				if($department_mail->level == 1) {
					foreach($departments->where('level1', $odjel_id) as $department) {
						array_push($odjel_email, $department->email);
					}
					
				} else {
					array_push($odjel_email, Department::where('id',$odjel_id)->first()->email);
				}

				$poruka = $presentation->subject;
				$edukacija = $educationTheme->name ;
				$link = "http://administracija.duplico.hr/admin/presentations/" . $presentation->id;
				/*
				foreach($odjel_email as $odjel_mail) {
					Mail::queue(
						'email.education',
						['poruka' => $poruka, 'edukacija' => $edukacija, 'link' => $link],
						function ($message) use ($odjel_mail , $poruka) {
							$message->to($odjel_mail)
								->from('info@duplico.hr', 'Duplico')
								->subject('Edukacija - objavljena je nova ' . ' tema');
						}
					);
				}
				*/
			}		
		}

		$message = session()->flash('success', 'Uspješno je dodana nova prezentacija');
		
		return redirect()->route('admin.presentations.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $presentation = '';
		$presentations = '';
		
		$educationThemes = EducationTheme::get();
		
		if($id != 0) {
			$presentation = Presentation::find($id);
		} else {
			$presentations = Presentation::get();
		}

	    return view('admin.presentations.show',['presentation' => $presentation,'presentations' => $presentations,'educationThemes' => $educationThemes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $presentation = Presentation::find($id);
		
		$educationThemes = EducationTheme::get();
		$registrations = Registration::get();
		
		return view('admin.presentations.edit',['presentation' => $presentation, 'educationThemes' => $educationThemes, 'registrations' => $registrations]);
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
         if(empty ($request['article'])) {
			$message = session()->flash('error', 'Nemoguće spremiti članak bez teksta.');
		
			return redirect()->back()->withFlashMessage($message);
		}
		$user1 = Sentinel::getUser();
		$employee = Employee::where('first_name',$user1->first_name)->where('last_name',$user1->last_name)->first();
		$presentation = Presentation::find($id);
	   
		$input = $request->except(['_token']);
		
		$data = array(
			'article'  	 	=> $input['article'],
			'subject'   	=> $input['subject'],
			'employee_id'   => $employee->id,
			'theme_id'  	=> $input['theme_id'],
			'status'  		=> $input['status']
		);
		
		$presentation->updatePresentation($data);
		
		$educationTheme = EducationTheme::where('id',$request['theme_id'])->first();

		$educations = Education::where('id',$educationTheme->education_id)->first();

		$departments = Department::get();
		if($educations->status == 'aktivna' && $presentation->status == 'aktivan') {
			$odjeli_id = explode(",",$educations->to_department_id);
			
			$odjel_email = array();
		//	$odjel_email = 'jelena.juras@duplico.hr';
			foreach($odjeli_id as $odjel_id){
				$department_mail = $departments->where('id',$odjel_id)->first();
				if($department_mail->level == 1) {
					foreach($departments->where('level1', $odjel_id) as $department) {
						array_push($odjel_email, $department->email);
					}
					
				} else {
					array_push($odjel_email, Department::where('id',$odjel_id)->first()->email);
				}

				$poruka = $presentation->subject;
				$edukacija = $educationTheme->name ;
				$link = "http://administracija.duplico.hr/admin/presentations/" . $presentation->id;
				/*
				foreach($odjel_email as $odjel_mail) {
					Mail::queue(
						'email.education',
						['poruka' => $poruka, 'edukacija' => $edukacija, 'link' => $link],
						function ($message) use ($odjel_mail , $poruka) {
							$message->to($odjel_mail)
								->from('info@duplico.hr', 'Duplico')
								->subject('Edukacija - objavljena je nova ' . ' tema');
						}
					);
				}
				*/
				
			}		
		}
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.presentations.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $presentation = Presentation::find($id);
		$presentation->delete();
		
		$message = session()->flash('success', 'Prezentacija je obrisana.');
		
		return redirect()->route('admin.presentations.index')->withFlashMessage($message);
    }
	
	
}
