<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;
use App\Models\Ad;
use App\Models\AdCategory;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\EmployeeTermination;
use App\Http\Request\AdRequest;
use Mail;

class AdController extends Controller
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
    public function index(Request $request)
    {
		$user = Sentinel::getUser();
		$employee = Employee::where('last_name', $user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		if(Sentinel::inRole('administrator')) {
			//$ads = Ad::where('category_id',$request->id )->orderBy('created_at','DESC')->get();
			$ads = Ad::orderBy('created_at','DESC')->get();
		} else {
			//$ads = Ad::where('category_id',$request->id )->orderBy('created_at','DESC')->where('employee_id',$employee->id )->get();
			if($employee) {
				$ads = Ad::orderBy('created_at','DESC')->where('employee_id',$employee->id )->get();
			} else {
				$ads = array();
			}
		}
		
	//	$category_id = $request->id;
		
		return view('admin.ads.index', ['ads'=>$ads]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$registrations = Registration::get();
		//$category = AdCategory::where('id',  $request['category_id'])->first();

		$employeeTerminations = EmployeeTermination::get();
		
		return view('admin.ads.create', ['registrations'=>$registrations, 'employeeTerminations'=>$employeeTerminations]);
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
		$user = Sentinel::getUser();
		$employee = Employee::where('last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$data = array(
			'employee_id'  		=> $employee->id,
		//	'category_id'  		=> $input['category_id'],
			'subject'  			=> $input['subject'],
			'description'  		=> $input['description']
		);
		
		$ad = new Ad();
		$ad->saveAd($data);
		
		$mail_svi = 'svi@duplico.hr';
		//$mail_svi = 'jelena.juras@duplico.hr';
		$link = 'http://administracija.duplico.hr/admin/oglasnik';
		//$link = 'http://localhost:8000/admin/oglasnik';
		$kategorija = $ad->category['name'];
			
		if(isset($input['fileToUpload'])) {
			$target_dir = 'storage/oglas/' . $ad->id . '/'; 
		
			if(!file_exists($target_dir)){
				mkdir($target_dir);
			}

			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
			$uploadOk = 1;
			
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
			// Check if image file is a actual image or fake image

			// Check if file already exists
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', 'Sorry, file already exists.');  
				$uploadOk = 0;
			}
			
			/* Check file size*/
			if ($_FILES["fileToUpload"]["size"] > 5000000) {
				return redirect()->back()->with('error', 'Sorry, your file is too large.');  
				$uploadOk = 0;
			}
			
			if($imageFileType == "exe" || $imageFileType == "bin") {
				return redirect()->back()->with('error', 'Nije dozvoljen unos exe, bin dokumenta');  
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				return redirect()->back()->with('error', 'Sorry, your file was not uploaded.'); 
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					Mail::queue(
						'email.oglas',
						['link' => $link, 'kategorija' => $kategorija],
						function ($message) use ($mail_svi) {
							$message->to($mail_svi)
									->from('info@duplico.hr')
									->subject('Naše Njuškalo - objavljen je novi oglas');
						}
					);

					$message = session()->flash('success', 'Oglas je spremljen');

					return redirect()->route('admin.ads.index')->withFlashMessage($message);
				} else {
					return redirect()->back()->with('error', 'Sorry, there was an error uploading your file.'); 
				}
			}
		} else {
			Mail::queue(
				'email.oglas',
				['link' => $link, 'kategorija' => $kategorija],
				function ($message) use ($mail_svi) {
					$message->to($mail_svi)
							->from('info@duplico.hr')
							->subject('Naše Njuškalo - objavljen je novi oglas');
				}
			);
			$message = session()->flash('success', 'Oglas je spremljen');

			return redirect()->route('admin.ads.index')->withFlashMessage($message);
		}
		
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ad = Ad::find($id);
		
		$path = 'storage/oglas/' . $ad->id;
		$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
		
		return view('admin.ads.show', ['ad'=>$ad, 'docs'=>$docs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$ad = Ad::find($id);
	
		return view('admin.ads.edit', ['ad'=>$ad]);
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
        $ad = Ad::find($id);
		
		$input = $request->except(['_token']);
		$user = Sentinel::getUser();
		$employee = Employee::where('last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$data = array(
			'employee_id'  		=> $employee->id,
		//	'category_id'  		=> $input['category_id'],
			'subject'  			=> $input['subject'],
			'description'  		=> $input['description']
		);
		
		$ad->updateAd($data);
		
		if(isset($input['fileToUpload'])) {
				$target_dir = 'storage/oglas/' . $ad->id . '/'; 
		
			if(!file_exists($target_dir)){
				mkdir($target_dir);
			}
			
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
			$uploadOk = 1;
			
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
			// Check if image file is a actual image or fake image

			// Check if file already exists
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', 'Sorry, file already exists.');  
				$uploadOk = 0;
			}
			
			/* Check file size*/
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				return redirect()->back()->with('error', 'Sorry, your file is too large.');  
				$uploadOk = 0;
			}
			
			if($imageFileType == "exe" || $imageFileType == "bin") {
				return redirect()->back()->with('error', 'Nije dozvoljen unos exe, bin dokumenta');  
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				return redirect()->back()->with('error', 'Sorry, your file was not uploaded.'); 
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

					$mail_svi = 'svi@duplico.hr';
					//$mail_svi = 'jelena.juras@duplico.hr';
					$link = 'http://administracija.duplico.hr/admin/oglasnik';
					//$link = 'http://localhost:8000/admin/oglasnik';
					$kategorija = $ad->category['name'];
					
					Mail::queue(
						'email.oglas',
						['link' => $link, 'kategorija' => $kategorija],
						function ($message) use ($mail_svi) {
							$message->to($mail_svi)
									->from('info@duplico.hr')
									->subject('Naše Njuškalo - objavljen je novi oglas');
						}
					);
					
			
					$message = session()->flash('success', 'Oglas je spremljen');

					return redirect()->route('admin.ads.index',['id'=> $input['category_id']])->withFlashMessage($message);
				} else {
					return redirect()->back()->with('error', 'Sorry, there was an error uploading your file.'); 
				}
			}
		}
			$message = session()->flash('success', 'Oglas je spremljen');

			return redirect()->route('admin.ads.index',['id'=> $input['category_id']])->withFlashMessage($message);
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = Ad::find($id);
		$ad->delete();
		
		$message = session()->flash('success', 'Oglas je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	/**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function oglasnik()
    {
		$ads = Ad::orderBy('created_at','DESC')->get();

		return view('admin.oglasnik',['ads'=> $ads]);
    }
}
