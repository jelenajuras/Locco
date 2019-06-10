<?php

namespace App\Services;

use Illuminate\View\Factory;
use Sentinel;
use App\Models\Post;

class CommentsService
{
	protected $view;
	
	public function __construct(Factory $view)
	{
		$this->view =  $view;
	}
	
	public function pendingComments()
	{
		$user_id = Sentinel::getuser()->id;
		$posts = Post::where('user_id', $user_id)->count();
		
		return $posts;
	}
}