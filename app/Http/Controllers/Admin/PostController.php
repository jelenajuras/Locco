<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Registration;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostRequest;
use Sentinel;


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
			$user_id= Sentinel::getUser()->id;
			$posts = Post::where('employee_id', $user_id)->orderBy('created_at','DESC')->get();
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
        $user_id = Sentinel::getUser()->id;
		$input = $request->except(['_token']);
		
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name','employees.email')->orderBy('employees.last_name','ASC')->get();
		$uprava = array();
		$svi = array();
		
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
			'employee_id'  	  => $user_id,
			'to_employee_id'  => $to_employee_id,
			'title'    		  => trim($input['title']),
			'content'  		  => $input['content']
		);
		
		$post = new Post();
		$post->savePost($data);
			
		$message = session()->flash('success', 'Poruka je poslana');
		
		//return redirect()->back()->withFlashMessage($message);
		return redirect()->route('admin.posts.index')->withFlashMessage($message);
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
	   $registrations = Registration::get();
		
		return view('admin.posts.edit', ['post' => $post])->with('registrations', $registrations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
		$post = Post::find($id);
		$input = $request->except(['_token']);

		$data = array(
			'title'    => trim($input['title']),
			'content'  => $input['content']
		);
		
		$post->updatePost($data);
		
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
}
