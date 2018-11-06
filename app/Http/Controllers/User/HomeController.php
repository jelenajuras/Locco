<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GodisnjiController;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\VacationRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Models\AfterHour;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use DateTime;
use DateInterval;
use DatePeriod;

class HomeController extends GodisnjiController
{
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
}
