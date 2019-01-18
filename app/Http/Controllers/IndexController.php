<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use App\Http\Requests\CommentRequest;
use App\Models\Employee;
use App\Models\VacationRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Models\AfterHour;
use App\Models\Registration;
use App\Models\EffectiveHour;
use App\Models\Questionnaire;
use App\Models\Evaluation;
use DateTime;
use Mail;

class IndexController extends Controller
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

		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$comments = Comment::get();
		$afterHours = AfterHour::get();
		
		$registration = Registration::where('registrations.employee_id', $employee->id)->first();
		$ech = EffectiveHour::where('employee_id', $employee->id)->first();
		
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;

		$zahtjeviD = VacationRequest::orderBy('GOpocetak','DESC')->take(30)->get();
		
		$posts = Post::where('to_employee_id',$employee->id)->take(5)->get();
		$posts2 = Post::where('employee_id',$user->id)->take(5)->get();
		$posts_Svima = Post::where('to_employee_id','784')->take(5)->get();
		
		$questionnaires = Questionnaire::where('status','aktivna')->get();
		$evaluations = Evaluation::where('employee_id',$employee->id)->get();
		
		return view('user.home', ['user' => $user,'registration' => $registration,'ech' => $ech,'employee' => $employee,'zahtjeviD' => $zahtjeviD,'ova_godina' => $ova_godina,'prosla_godina' => $prosla_godina,'posts' => $posts,'posts2' => $posts2,'comments' => $comments,'afterHours' => $afterHours,'questionnaires' => $questionnaires]);

    }
	
	public function show($slug)
	{
		//$post = Post::where('slug',$slug)->first();
		
		//return view('post.show')->with('post', $post);
	}
	
	public function storeComment(CommentRequest $request)
	{
		$post = Post::where('id', $request->post_id)->first();

		$user_id = Sentinel::getUser()->id;

		$data = array(
			'user_id'  => $user_id,                     // ili Sentinel::getUser()->id
			'post_id'  =>  $request->get('post_id'),    //$input['post_id'],
			'content'  =>  $request->get('content')     // )$input['content']
		);
		$comment = new Comment();
		$comment->saveComment($data);
		
		$employee = Employee::where('id', $post->employee_id)->first();
		$email = $employee->email;
		
		$poruka = "Dobio si odgovor na poruku.";
		$link = 'http://administracija.duplico.hr/admin/posts/' . $post->id;
		
		Mail::queue(
			'email.odgovorRaspored',
			['poruka' => $poruka, 'link' => $link],
			function ($message) use ($email, $employee) {
				$message->to($email)
					->from($email, $employee->first_name . ' ' .  $employee->last_name)
					->subject('Odgovor na zahtjev za raspored');
			}
		);
		
		$message = session()->flash('success', 'You have successfully addad a new comment.');
		
		return redirect()->back()->withFlashMessage($message);
		//return redirect()->route('admin.posts.index')->withFlashMessage($message);
	}
	
}