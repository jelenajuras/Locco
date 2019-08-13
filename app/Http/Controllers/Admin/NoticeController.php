<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sentinel;
use App\Models\Notice;
use App\Models\Employee;
use App\Models\EmployeeTermination;
use App\Models\Registration;
use App\Models\Department;
use App\Models\Employee_department;
use App\Http\Requests\NoticeRequest;
use App\Http\Controllers\Controller;
use Mail;
use DB;

class NoticeController extends Controller
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
        $notices = Notice::orderBy('created_at','DESC')->get();
		
		return view('admin.notices.index',['notices'=>$notices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user1 = Sentinel::getUser();
		$user = Employee::where('first_name',$user1->first_name)->where('last_name',$user1->last_name)->first();
		$user = $user->id;

		$departments0 = Department::where('level',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level',1)->orderBy('name','ASC')->get();
		$departments2 = Department::where('level',2)->orderBy('name','ASC')->get();
		$employee_departments = Employee_department::get();
		
		return view('admin.notices.create',['user'=>$user, 'departments0'=>$departments0, 'departments1'=>$departments1, 'departments2'=>$departments2, 'employee_departments'=>$employee_departments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoticeRequest $request)
    {
		$to_department_id = implode(',', $request['to_department_id']);

		$notice = $request['notice'];
		$dom = new \DomDocument();
		$dom->loadHtml(mb_convert_encoding($notice, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		$images = $dom->getElementsByTagName('img');
		
		foreach($images as $k => $img){
			$data = $img->getAttribute('src');
			$dataFilename = $img->getAttribute('data-filename');
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$image_name= "/img/notices/" . $dataFilename;
			$path = public_path() .  $image_name;
			file_put_contents($path, $data);
			$img->removeAttribute('src');
			$img->setAttribute('src', $image_name);
		}
			
		$notice = $dom->saveHTML();

		$data1 = array(
			'employee_id'   	=> $request['employee_id'],
			'to_department_id'  => $to_department_id,
			'subject'  			=> $request['subject'],
			'notice'  			=> $notice
		);
		
		$notice1 = new Notice();
		$notice1->saveNotice($data1);

		
		foreach($request['to_department_id'] as $department) {
			$department = Department::where('id', $department)->first();
			$prima = $department->email;
			
			Mail::queue(
				'email.notice',
				['poruka' => $notice1->subject],
				function ($message) use ($prima) {
					$message->to($prima)
						->from('info@duplico.hr', 'Duplico')
						->subject('Obavijest uprave');
				}
			);
			
		}
		
		$message = session()->flash('success', 'Obavijest je poslana');
		
		return redirect()->route('admin.notices.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notice = Notice::find($id);

		return view('admin.notices.show', ['notice' => $notice]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user1 = Sentinel::getUser();
		$user = Employee::where('first_name',$user1->first_name)->where('last_name',$user1->last_name)->first();
		$user = $user->id;
		
		$notice = Notice::find($id);
		$departments = Department::orderBy('name','ASC')->get();

		return view('admin.notices.edit', ['notice' => $notice, 'user' => $user, 'departments'=>$departments]);
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
		$notice = Notice::find($id);
		
		$poruka = $request['notice'];

		$dom = new \DomDocument();
		$dom->loadHtml(mb_convert_encoding($poruka, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		$images = $dom->getElementsByTagName('img');
		
		foreach($images as $k => $img){
            $data = $img->getAttribute('src');
			$dataFilename = $img->getAttribute('data-filename');
		
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= "/img/notices/" . $dataFilename;
            $path = public_path() .  $image_name;
            file_put_contents($path, $data);
            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }

        $poruka = $dom->saveHTML();
		$to_department_id = implode(',', $request['to_department_id']);
		$input = $request->except(['_token']);
		
		$data1 = array(
			'employee_id'   	=> $input['employee_id'],
			'to_department_id'  => $to_department_id,
			'subject'  			=> $input['subject'],
			'notice'  			=> $poruka
		);
		
		$notice->updateNotice($data1);

		foreach($request['to_department_id'] as $department) {
			$department = Department::where('id', $department)->first();
			$prima = $department->email;
			
			Mail::queue(
				'email.notice',
				['poruka' => $notice->subject],
				function ($message) use ($prima) {
					$message->to($prima)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak obavijesti');
				}
			);
			
		}

		$message = session()->flash('success', 'Obavijest je ispravljena');
		
		return redirect()->route('admin.notices.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notice = Notice::find($id);
		$notice->delete();
		
		$message = session()->flash('success', 'Obavijest je obrisana.');
		
		return redirect()->route('admin.notices.index')->withFlashMessage($message);
    }
}
