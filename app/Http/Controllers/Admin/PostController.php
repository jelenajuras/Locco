<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Employee_department;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostRequest;
use Sentinel;
use Mail;

class PostController extends Controller
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
		if(Sentinel::inRole('administrator')) {
			$posts = Post::orderBy('created_at','DESC')->get();
		} else {
			$user = Sentinel::getUser();
			$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
			$posts = Post::where('employee_id', $employee->id)->orderBy('created_at','DESC')->get();
		}
	
		return view('admin.posts.index',['posts'=>$posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->where('odjava',null)->select('registrations.*','employees.first_name','employees.last_name','employees.email')->orderBy('employees.last_name','ASC')->get();

		$departments = Department::orderBy('name','ASC')->get();

		return view('admin.posts.create',['registrations'=> $registrations,'departments'=> $departments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
		
		$user = Sentinel::getUser();
		$input = $request->except(['_token']);
		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$departments = Department::get();
		$registrations = Registration::where('odjava',null)->get();
		$registration =  $registrations->where('employee_id', $employee->id)->first();

		$employee_departments = Employee_department::get();
		$mail_to_employees = array();
		$emails = array();
		
		// zahtjev za raspored
		if(isset($input['tip']) && $input['tip'] == 'raspored'){
			$nadredjeni_id = '';
			$nadredjeni = '';
			
			$to_department_id = Department::where('email','uprava@duplico.hr')->first()->id;
			
			if($registration->prvi_userId != 0){
				$nadredjeni_id = $registration->prvi_userId;
			} else {
				$nadredjeni_id = $registration->user_id;
			}
			
			$nadredjeni_mail = Employee::where('id', $nadredjeni_id)->first()->email;

			if($input['datum'] === '') {
				$message = session()->flash('error', 'Nemoguće poslati zahtjev, nije upisan datum');
				return redirect()->back()->withFlashMessage($message);
			}
			$poruka = $input['content'] . ' ' . date_format(date_create($input['datum']),'d.m.Y') .  ' od ' . $input['vrijemeOd'] . ' do '. $input['vrijemeDo'] . ' h' ;
			
			$data = array(
				'employee_id'  	  => $employee->id,
				'to_department_id'=> $to_department_id,
				'to_employee_id'  => $nadredjeni_id,
				'title'    		  => trim($input['title']),
				'content'  		  => $poruka
			);
			
			$post = new Post();
			$post->savePost($data);

			$mailovi = ['koordinacija@duplico.hr','uprava@duplico.hr']; 
			// $mailovi = ['uprava@duplico.hr', $nadredjeni_mail];
			// $mailovi = ['jelena.juras@duplico.hr','jelena.juras@duplico.hr'];
			$link = 'http://administracija.duplico.hr/admin/posts/' . $post->id;
			
			try {
				foreach($mailovi as $mail) {
					Mail::queue(
						'email.raspored',
						['employee' => $employee, 'poruka' => $poruka, 'link' => $link],
						function ($message) use ($mail, $employee) {
							$message->to($mail)
								->from($employee->email, $employee->first_name . ' ' .  $employee->last_name)
								->subject('Zahtjev za raspored - ' .  $employee->first_name . ' ' .  $employee->last_name);
						}
					);
				}
			} catch (\Throwable $th) {
				$message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
				return redirect()->back()->withFlashMessage($message);
			}
			
			$message = session()->flash('success', 'Poruka je poslana');
			return redirect()->back()->withFlashMessage($message);
			
		} else {
			foreach ( $request['to_department_id'] as $department_id) {
				$department = Department::where('id', $department_id)->first();

				$data = array(
					'to_department_id' => $department->id,
					'title'    		  => trim($input['title']),
					'content'  		  => $input['content']
				);
				if ($department['name'] != 'uprava') {
					$data += ['employee_id'  => $employee->id];
				}

				$post = new Post();
				$post->savePost($data);

				$url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				$poruka = $url . '/' . $post->id;

				if($department->level == 0 && $department->name != 'Uprava' ) {
					foreach ($registrations as $registration) {
						if($registration->employee['email'] != null) {
							array_push($emails, $registration->employee['email']);
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
						if($registrations->where('employee_id', $to_employee->employee_id)->first() && $registrations->where('employee_id', $to_employee->employee_id)->first()->employee['email'] != null ) {
							array_push($emails, $registrations->where('employee_id', $to_employee->employee_id)->first()->employee['email']);
						}
					}
				} else  {
					$department_level2 = $department->where('id', $department->id )->first();
					$employees_dep = $employee_departments->where('department_id', $department_level2->id );
	
					foreach ($employees_dep as $employee_dep) {
						array_push($mail_to_employees, $employee_dep);
					}
					foreach ($mail_to_employees as $to_employee) {
						if($registrations->where('employee_id', $to_employee->employee_id)->first() && $registrations->where('employee_id', $to_employee->employee_id)->first()->employee['email'] != null ) {
							array_push($emails, $registrations->where('employee_id', $to_employee->employee_id)->first()->employee['email']);
						}
					}
				}

				// mail ide na mail svih zaposlenika odjala - NE na mail odjela

			//	$email = $department['email'];
				$name = $department['name'];  //poruka za odjel
			}

			if(isset($emails) && count($emails) > 0) {
				if(isset($input['tip']) && $input['tip'] == 'prijava' ||  $input['tip'] == 'odjava'){
					$empl_department = $employee->work->department['name'];   //odjel koji je poslao poruku
					
					try {
						foreach(array_unique($emails) as $email) {
							Mail::queue(
								'email.post_tip',
								['poruka' => $poruka, 'post' => $post, 'empl_department' => $empl_department],
								function ($message) use ( $email, $input, $name) {
									$message->to($email)
										->from('info@duplico.hr', 'Duplico')
										->subject('Nova poruka za odjel - ' . $name );
								}
							);
						}
					} catch (\Throwable $th) {
						$message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
						return redirect()->back()->withFlashMessage($message);
					}
				} else {
					try {
						foreach(array_unique($emails) as $email) {
							Mail::queue(
								'email.post',
								['poruka' => $poruka, 'employee' => $employee],
								function ($message) use ( $email, $input, $name) {
									$message->to($email)
										->from('info@duplico.hr', 'Duplico')
										->subject('Nova poruka za odjel - ' . $name );
								}
							);
						}
					} catch (\Throwable $th) {
						$message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
						return redirect()->back()->withFlashMessage($message);
					}
				}
			}
			$message = session()->flash('success', 'Poruka je poslana');
			return redirect()->route('admin.posts.index')->withFlashMessage($message);
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
		$post = Post::find($id);

		return view('admin.posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->where('odjava',null)->select('registrations.*','employees.first_name','employees.last_name','employees.email')->orderBy('employees.last_name','ASC')->get();
		$departments = Department::orderBy('name','ASC')->get();

		return view('admin.posts.edit',['post' => $post, 'registrations'=> $registrations,'departments'=> $departments]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
		$post = Post::find($id);
		$input = $request->except(['_token']);
		
		$data = array(
			'title'    => trim($input['title']),
			'content'  => $input['content']
		);

		$post->updatePost($data);
		
		$department = Department::where('id', $post->to_department_id)->first();

		$url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$poruka = $url . '/' . $post->id;
		
		$email = $department['email'];
		$name = $department['name'];

		Mail::queue(
			'email.post',
			['poruka' => $poruka],
			function ($message) use ( $email, $input, $name) {
				$message->to($email)
					->from('info@duplico.hr', 'Duplico')
					->subject('Ispravak poruke odjelu - ' . $name );
			}
		);

		$message = session()->flash('success', 'Poruka je promijenjena');
		
		return redirect()->route('admin.posts.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
		$post->delete();
		
		$message = session()->flash('success', 'You have successfully delete a post.');
		
		return redirect()->route('admin.posts.index')->withFlashMessage($message);
    }
	
	public function shedulePost()
	{
		$user = Sentinel::getUser();
		return view('admin.shedulePost', ['user' => $user]);
	}
}
