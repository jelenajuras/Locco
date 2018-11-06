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
use DateTime;
use DateInterval;
use DatePeriod;

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
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;

		$zahtjeviD = VacationRequest::orderBy('GOpocetak','DESC')->take(30)->get();
		
		$posts = Post::where('to_employee_id',$employee->id)->take(5)->get();
		$posts2 = Post::where('employee_id',$user->id)->take(5)->get();
		$posts_Svima = Post::where('to_employee_id','784')->take(5)->get();
		
		return view('user.home')->with('user', $user)->with('registration', $registration)->with('employee', $employee)->with('zahtjeviD', $zahtjeviD)->with('ova_godina', $ova_godina)->with('prosla_godina', $prosla_godina)->with('posts', $posts)->with('posts2', $posts2)->with('posts2', $posts2)->with('comments', $comments)->with('afterHours', $afterHours);

    }
	
	public function show($slug)
	{
		//$post = Post::where('slug',$slug)->first();
		
		//return view('post.show')->with('post', $post);
	}
	
	public function storeComment(CommentRequest $request)
	{
		$user_id = Sentinel::getUser()->id;
		// $comments = Comment::where('post_id', $post->id)->get();  može i tako, nije dobra praksa, bolje preko relacije
		
		//$input = $request->except(['_token']); // bez tokena
		//$input = $request->all();
		//$input = $request->get('post_id');
		//dd($input);
		
		$data = array(
			'user_id'  => $user_id,                     // ili Sentinel::getUser()->id
			'post_id'  =>  $request->get('post_id'),    //$input['post_id'],
			'content'  =>  $request->get('content')     // )$input['content']
		);
		$comment = new Comment();
		$comment->saveComment($data);
		
		$message = session()->flash('success', 'You have successfully addad a new comment.');
		
		return redirect()->back()->withFlashMessage($message);
		//return redirect()->route('admin.posts.index')->withFlashMessage($message);
	}
	
}