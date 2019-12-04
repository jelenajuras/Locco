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
		$departments = Department::orderBy('name','ASC')->get();

		$user = Sentinel::getUser();
		$employee = Employee::where('first_name',$user->first_name)->where('last_name',$user->last_name)->first();
		$reg_employee = Registration::where('employee_id', $employee->id)->first();		
		$employee_departments = array();
		
        if ($reg_employee) {
            if ( Sentinel::inRole('administrator')) {
				return view('admin.notices.index', ['notices'=>$notices,'employee'=>$employee,'departments'=>$departments ]);    
            } else {
                $departments = Employee_department::where('employee_id',$employee->id )->get();
                foreach( $departments as $department) {
                    array_push($employee_departments, $department->department_id);
                    array_push($employee_departments, 10); // odjel "svi"
                    if ($department->level == 2 ) {
                        array_push($employee_departments,$department->level1);
                    } 
				}

                return view('admin.notices.index',['notices'=>$notices, 'employee_departments' => $employee_departments,'employee'=>$employee,'departments'=>$departments  ]);
            }
			
		} else {
			array_push($employee_departments, 10); // odjel "svi"
			
			return view('admin.notices.index', ['notices'=>$notices, 'employee_departments' => $employee_departments,'departments'=>$departments ]);       
        }
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

		$departments = Department::orderBy('name','ASC')->get();
		
		$employee_departments = Employee_department::get();
		
		return view('admin.notices.create',['user'=>$user, 'departments'=>$departments, 'employee_departments'=>$employee_departments]);
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
		$departments = Department::get();
		$employees = Registration::where('odjava',null)->get();
		$employee_departments = Employee_department::get();
		$mail_to_employees = array();
		$emails = array();
		
		$notice = $request['notice'];
		$dom = new \DomDocument();
		try {
			$dom->loadHtml(mb_convert_encoding($notice, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		} catch (\Throwable $th) {
			$message = session()->flash('error', 'Nešto je pošlo krivo...');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
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
			'type'  			=> $request['type'],
			'subject'  			=> $request['subject'],
			'notice'  			=> $notice
		);
		
		$notice1 = new Notice();
		$notice1->saveNotice($data1);

		$title = 'Nova poruka';

		if($notice1->type == 'najava') {
			$title = 'Najava aktivnosti';
		}
		if($notice1->type == 'uprava') {
			$title = 'Obavijest uprave';
		}

		foreach ($request['to_department_id']  as $department_id) {
			$department = $departments->where('id', $department_id )->first();

			if($department->level == 0 && $department->name != 'Uprava' ) {
				foreach ($employees as $employee) {
					if($employee->employee['email'] != null) {
						array_push($emails, $employee->employee['email']);
					}
				}
			} else if ($department->level == 1) {
				$departments_level2 = $departments->where('level1', $department->id );

				foreach ($departments_level2 as $department_level2) {
					$employees_dep = $employee_departments->where('department_id', $department_level2->id );

					foreach ($employees_dep as $employee_dep) {
						array_push($mail_to_employees, $employee_dep);
					}
				}
				foreach ($mail_to_employees as $to_employee) {
					if($employees->where('employee_id', $to_employee->employee_id)->first() && $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email'] != null ) {
						array_push($emails, $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email']);
					}
				}
			} else  {
				$department_level2 = $department->where('id', $department->id )->first();
				$employees_dep = $employee_departments->where('department_id', $department_level2->id );

				foreach ($employees_dep as $employee_dep) {
					array_push($mail_to_employees, $employee_dep);
				}
				foreach ($mail_to_employees as $to_employee) {
					if($employees->where('employee_id', $to_employee->employee_id)->first() && $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email'] != null ) {
						array_push($emails, $employees->where('employee_id', $to_employee->employee_id)->first()->employee['email']);
					}
				}
			}
		}
		$link = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/notices/'. $notice1->id ;

		try {
			foreach(array_unique($emails) as $email_to_employee) {

				Mail::queue(
					'email.notice',
					['poruka' => $notice1->subject, 'type' => $notice1->type, 'link' => $link],
					function ($message) use ($email_to_employee, $title) {
						$message->to($email_to_employee)
							->from('info@duplico.hr', 'Duplico')
							->subject($title);
					}
				);
			}
		} catch (\Throwable $th) {
			$message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
			return redirect()->back()->withFlashMessage($message);
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
			'type'  			=> $request['type'],
			'subject'  			=> $input['subject'],
			'notice'  			=> $poruka
		);
		 
		$notice->updateNotice($data1);

		foreach ($request['to_department_id']  as $department_id) {
			$department = $departments->where('id', $department_id )->first();

			if($department->level == 0 && $department->name != 'Uprava' ) {
				foreach ($employees as $employee) {
					if($employees->where('employee_id', $employee->employee_id)->first()->employee['email'] != null) {
						array_push($emails, $employees->where('employee_id', $employee->employee_id)->first()->employee['email']);
					}
				}
			} else if ($department->level == 1) {
				$departments_level2 = $departments->where('level1', $department->id );

				foreach ($departments_level2 as $department_level2) {
					$employees_dep = $employee_departments->where('department_id', $department_level2->id );

					foreach ($employees_dep as $employee_dep) {
						array_push($mail_to_employees, $employee_dep);
					}
				}
				foreach ($mail_to_employees as $employee) {
					if($employees->where('employee_id', $employee->employee_id)->first() && $employees->where('employee_id', $employee->employee_id)->first()->employee['email'] != null ) {
						array_push($emails, $employees->where('employee_id', $employee->employee_id)->first()->employee['email']);
					}
				}
			} else  {
				$department_level2 = $department->where('id', $department->id )->first();
				$employees_dep = $employee_departments->where('department_id', $department_level2->id );

				foreach ($employees_dep as $employee_dep) {
					array_push($mail_to_employees, $employee_dep);
				}
				foreach ($mail_to_employees as $employee) {
					if($employees->where('employee_id', $employee->employee_id)->first() && $employees->where('employee_id', $employee->employee_id)->first()->employee['email'] != null ) {
						array_push($emails, $employees->where('employee_id', $employee->employee_id)->first()->employee['email']);
					}
				}
			}
		}
		$link = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/notices/'. $notice1->id ;

		try {
			foreach(array_unique($emails) as $email_to_employee) {

				Mail::queue(
					'email.notice',
					['poruka' => $notice->subject, 'type' => $notice1->type, 'link' => $link ],
					function ($message) use ($email_to_employee) {
						$message->to($email_to_employee)
							->from('info@duplico.hr', 'Duplico')
							->subject('Ispravak obavijesti');
					}
				);
			}
		} catch (\Throwable $th) {
			$message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
			return redirect()->back()->withFlashMessage($message);
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
