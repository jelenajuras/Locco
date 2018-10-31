<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use App\Models\Registration;
use App\Models\Employee;
use PDF;

class DocumentController extends Controller
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
		$user = Sentinel::getUser();
		
		$user_name = explode('.',strstr($user->email,'@',true));
		if(count($user_name) == 2) {
			$user_name = $user_name[1] . '_' . $user_name[0];
		}else {
			$user_name = $user_name[0];
		}
		
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		$employee = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->where('employees.first_name',$user->first_name)->where('employees.last_name',$user->last_name)->first();
		
		$path = 'storage/' . $user_name;
		if($path){
			$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
		}else {
			$docs = '';
		}
		
		$path2 = 'storage/svi/';
		if($path2){
			$docs2 = array_diff(scandir($path2), array('..', '.', '.gitignore'));
		}else {
			$docs2 = '';
		}
		
		return view('documents.index',['docs' => $docs,'docs2' => $docs2, 'user_name' => $user_name, 'registrations'=>$registrations,'registrations'=>$registrations,'employee'=>$employee]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$employee = Employee::where('id',$request->employee_id)->first();

		if($request['employee_id'] == 'svi'){
			$user_name = 'svi';
		} else {
			$user_name = explode('.',strstr($employee->email,'@',true));
			$user_name = $user_name[1] . '_' . $user_name[0];
		}
		
		$target_dir = 'storage/' . $user_name . "/";  //specifies the directory where the file is going to be placed
		
		// Create directory
		
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
		
		if(!$target_file){
			mkdir($target_dir);
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
		}

		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
		// Check if image file is a actual image or fake image

		// Check if file already exists
		if (file_exists($target_file)) {
			return redirect()->back()->with('danger', 'Sorry, file already exists.');  
			$uploadOk = 0;
		}
		/* Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			return redirect()->back()->with('danger', 'Sorry, your file is too large.');  
			$uploadOk = 0;
		}*/
		/* Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" && $imageFileType != "pdf") {
			return redirect()->back()->with('danger', 'Dozvoljen unos samo jpg, png, pdf, gif');  
			$uploadOk = 0;
		}*/
		if($imageFileType == "exe" || $imageFileType == "bin") {
			return redirect()->back()->with('danger', 'Nije dozvoljen unos exe, bin dokumenta');  
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			return redirect()->back()->with('danger', 'Sorry, your file was not uploaded.'); 
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				return redirect()->back()->with('success',"The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
			} else {
				return redirect()->back()->with('danger', 'Sorry, there was an error uploading your file.'); 
			}
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	public function generate_pdf($id) 
	{
		return $pdf->inline($id);
	}
}
