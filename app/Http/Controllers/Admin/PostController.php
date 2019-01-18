<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Registration;
use App\Models\Employee;
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
			$user= Sentinel::getUser();
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
        $registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name','employees.email')->orderBy('employees.last_name','ASC')->get();
		
		return view('admin.posts.create',['registrations'=> $registrations]);
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
		
		if($input['datum'] === '') {
			$message = session()->flash('error', 'Nemoguće poslati zahtjev, nije upisan datum');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$registration = Registration::join('works','works.id','registrations.radnoMjesto_id')->select('registrations.*','works.user_id','works.prvi_userId')->where('registrations.employee_id', $employee->id)->first();
		
		$nadredjeni_id = '';
		$nadredjeni = '';
		
		if($registration->prvi_userId != 0){
			$nadredjeni_id = $registration->prvi_userId;
		} else {
			$nadredjeni_id = $registration->user_id;
		}
		
		$nadredjeni = Employee::where('id', $nadredjeni_id)->first()->email;
		
		if($input['tip'] == 'raspored'){
			$poruka = $input['content'] . ' ' . date_format(date_create($input['datum']),'d.m.Y') . '. ' . $input['content2'] . ' ' .  $input['vrijemeOd'] . ' do '. $input['vrijemeDo'] . ' h' ;
			
			$to_employee_id = '877282';
			
			$data = array(
				'employee_id'  	  => $employee->id,
				'to_employee_id'  => $to_employee_id,
				'title'    		  => trim($input['title']),
				'content'  		  => $poruka
			);
			
			$post = new Post();
			$post->savePost($data);
			$post_id = $post->id;
			
			$mailovi = ['uprava@duplico.hr',$nadredjeni];
			//$mailovi = ['jelena.juras@duplico.hr','jelena.juras@duplico.hr'];
			$link = 'http://administracija.duplico.hr/admin/posts/' . $post_id;

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
			
			$message = session()->flash('success', 'Poruka je poslana');
			return redirect()->route('home')->withFlashMessage($message);
			
		} else {
			if($input['to_employee_id'] == 'uprava'){
				$to_employee_id = '877282';
			} elseif($input['to_employee_id'] == 'pravni'){
				$to_employee_id = '772864';
			} elseif($input['to_employee_id'] == 'racunovodstvo'){
				$to_employee_id = '72286';
			} elseif($input['to_employee_id'] == 'it'){
				$to_employee_id = '48758322';
			}
			
			$data = array(
				'employee_id'  	  => $employee->id,
				'to_employee_id'  => $to_employee_id,
				'title'    		  => trim($input['title']),
				'content'  		  => $input['content']
			);
			
			$post = new Post();
			$post->savePost($data);
			
			$proba = 'jelena.juras@duplico.hr';
			$uprava = 'uprava@duplico.hr';
			$pravni = 'pravni@duplico.hr';
			$it = 'itpodrska@duplico.hr';
			$racunovodstvo = 'racunovodstvo@duplico.hr';
			
			$post_id = $post->id;
			$poruka = 'http://localhost:8000/admin/posts/' . $post_id;
			
			if($input['to_employee_id'] == 'uprava'){
				Mail::queue(
					'email.post',
					['employee' => $employee, 'poruka' => $poruka, 'uprava' => $uprava],
					function ($message) use ($uprava, $employee) {
						$message->to($uprava)
							->from('info@duplico.hr', 'Duplico')
							->subject('Poruka upravi - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
			}
			if($input['to_employee_id'] == 'pravni'){
				Mail::queue(
					'email.post',
					['employee' => $employee, 'poruka' => $poruka, 'pravni' => $pravni],
					function ($message) use ($pravni, $employee) {
						$message->to($pravni)
							->from('info@duplico.hr', 'Duplico')
							->subject('Poruka pravnom odjelu - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
			}
			if($input['to_employee_id'] == 'it'){
				Mail::queue(
					'email.post',
					['employee' => $employee, 'poruka' => $poruka, 'it' => $it],
					function ($message) use ($it, $employee) {
						$message->to($it)
							->from('info@duplico.hr', 'Duplico')
							->subject('Poruka IT odjelu - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
			}
			if($input['to_employee_id'] == 'racunovodstvo'){
				Mail::queue(
					'email.post',
					['employee' => $employee, 'poruka' => $poruka, 'proba' => $proba],
					function ($message) use ($racunovodstvo, $employee) {
						$message->to($racunovodstvo)
							->from('info@duplico.hr', 'Duplico')
							->subject('Poruka racunovodstvu - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
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

		return view('admin.posts.edit', ['post' => $post]);
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
		
		$proba = 'jelena.juras@duplico.hr';
		$uprava = 'uprava@duplico.hr';
		$pravni = 'pravni@duplico.hr';
		$it = 'itpodrska@duplico.hr';
		$racunovodstvo = 'racunovodstvo@duplico.hr';
		
		$post_id = $post->id;
		$poruka = 'http://localhost:8000/admin/posts/' . $post_id;
				   
		if($input['to_employee_id'] == 'uprava'){
			Mail::queue(
				'email.post',
				['employee' => $employee, 'poruka' => $poruka, 'uprava' => $uprava],
				function ($message) use ($uprava, $employee) {
					$message->to($uprava)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak poruke upravi - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
		}
		if($input['to_employee_id'] == 'pravni'){
			Mail::queue(
				'email.post',
				['employee' => $employee, 'poruka' => $poruka, 'pravni' => $pravni],
				function ($message) use ($pravni, $employee) {
					$message->to($pravni)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak poruke pravnom odjelu - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
		}
		if($input['to_employee_id'] == 'it'){
			Mail::queue(
				'email.post',
				['employee' => $employee, 'poruka' => $poruka, 'it' => $it],
				function ($message) use ($it, $employee) {
					$message->to($it)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak poruke IT odjelu - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
		}
		if($input['to_employee_id'] == 'racunovodstvo'){
			Mail::queue(
				'email.post',
				['employee' => $employee, 'poruka' => $poruka, 'proba' => $proba],
				function ($message) use ($racunovodstvo, $employee) {
					$message->to($racunovodstvo)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak poruke racunovodstvu - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
		}
		
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
