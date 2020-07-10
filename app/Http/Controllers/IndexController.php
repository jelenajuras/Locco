<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use App\Http\Requests\CommentRequest;
use App\Models\Employee;
use App\Models\Employee_department;
use App\Models\VacationRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Department;
use App\Models\AfterHour;
use App\Models\Registration;
use App\Models\EffectiveHour;
use App\Models\Questionnaire;
use App\Models\Education;
use App\Models\Presentation;
use App\Models\Ad;
use App\Models\CatalogManufacturer;
use App\Models\Event;
use App\Models\EmployeeTask;
use App\Models\TemporaryEmployee;
use DateTime;
use Mail;
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
		if(Sentinel::check()) {
			$user = Sentinel::getUser();
			$employee = Employee::where('last_name',$user->last_name)->where('first_name',$user->first_name)->first();			
			$temporary = TemporaryEmployee::where('last_name', $user->last_name)->where('first_name', $user->first_name)->first();
			
			if($employee ) {
				$reg_employee = Registration::where('employee_id', $employee->id)->first();
				//$employeeDepartments = Employee_department::where('employee_id', $employee->id)->get();
				$datum = new DateTime('now');    /* današnji dan */
				$ova_godina = date_format($datum,'Y');

				if(Sentinel::inRole('administrator')) {
					$zahtjevi_neodobreni = VacationRequest::where('odobreno',null)->orderBy('start_date','DESC')->get();
					$zahtjevi_odobreni = VacationRequest::where('odobreno','DA')->whereYear('end_date',$ova_godina )->orderBy('start_date','DESC')->get()->take(30);
					$afterHours = AfterHour::where('odobreno', null)->orderBy('datum','DESC')->get();
				} else {
					$zahtjevi_neodobreni = null;
					$zahtjevi_odobreni = null;
					$afterHours = null;
				}

				$ech = EffectiveHour::where('employee_id', $employee->id)->first();
				
				// ANKETE
				$questionnaires = Questionnaire::where('status','aktivna')->get();
				
				// Edukacija
				$educations = Education::where('status','aktivna')->get();
				$presentations = Presentation::where('status','aktivan')->get();
				$ads = Ad::get();
	
				// Proizvođači opreme
				$catalog_manufacturers = CatalogManufacturer::get()->count();
				//$catalog_categories = CatalogCategory::get()->count();
			
				// Zadaci
			//	$employee_tasks = EmployeeTask::where('employee_id', $employee->id)->get();
				$employee_tasks = EmployeeTask::get();
				
				$dataArr = array();
				// Kalendar
				$today = date('Y-m-d');
				$select_day = explode('-',$today);  //get from URL
				$dan_select = $select_day[2];
				$mj_select = $select_day[1];
				$god_select = $select_day[0];
	
				$registrations = Registration::where('odjava',null)->get();
				foreach($registrations as $registration) {
					$dan = $god_select . '-' . date('m-d', strtotime($registration->employee['datum_rodjenja']));
					$dan_lp  = date('Y-m-d', strtotime($registration->lijecn_pregled));
					array_push($dataArr, ['name' => 'birthday', 'date' => $dan, 'employee' => $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] ]);
					array_push($dataArr, ['name' => 'LP', 'date' => $dan_lp, 'employee' => $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] ]);
				}
				if(count($employee_tasks)>0) {
					foreach ($employee_tasks as $task) {
						if($task->task->active == 1) {
							array_push($dataArr, ['name' => 'task', 'date' => date('Y-m-d',strtotime($task->created_at)) , 'employee' => $task->employee['first_name'] . ' ' .  $task->employee['last_name'] , 'task' => $task->task->task] );
						}					
					}
				}			

				$absences = VacationRequest::where('odobreno','DA')->whereYear('start_date', $god_select)->get();
				$absences = $absences->merge(VacationRequest::where('odobreno','DA')->whereYear('end_date', $god_select)->get());
				foreach($absences as $absence) {
					$begin = new DateTime($absence->start_date);
					$end = new DateTime($absence->end_date);
					$end->setTime(0,0,1);
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);
					foreach ($period as $dan) {
						if(date_format($dan,'Y') >= $god_select) {  // ako je trenutna godina
							if($absence->zahtjev == 'Izlazak') {
								array_push($dataArr, ['name' => 'absence', 'type' => $absence->zahtjev, 'date' => date_format($dan,'Y-m-d'), 'employee' => $absence->employee['first_name'] . ' ' . $absence->employee['last_name'], 'time' => date( 'G:i',(strtotime($absence->start_time))) . '-' . date( 'G:i',(strtotime($absence->end_time))) ]);
							} else {
								array_push($dataArr, ['name' => 'absence', 'type' => $absence->zahtjev, 'date' => date_format($dan,'Y-m-d'), 'employee' => $absence->employee['first_name'] . ' ' . $absence->employee['last_name'] ]);
							}
						}
					}
				}
	
				$events = Event::whereYear('date1', $god_select)->get();
				
				foreach($events as $event) {
					$begin1 = new DateTime($event->date1);
					$end1 = new DateTime($event->date2);
					$end1->setTime(0,0,1);
					$interval1 = DateInterval::createFromDateString('1 day');
					$period1 = new DatePeriod($begin1, $interval1, $end1);
					foreach ($period1 as $dan1) {
						if(date_format($dan,'Y') >= $god_select) {  // ako je trenutna godina
							array_push($dataArr, ['name' => 'event', 'type' => $event->type, 'date' => date_format($dan1,'Y-m-d'), 'employee' => $event->employee['first_name']  . ' ' .  $event->employee['last_name'], 'title' => $event->title,'time' => date( 'G:i',(strtotime($event->time1))) . '-' . date( 'G:i',(strtotime($event->time2))) ]);
						}
					}
				}
	
				return view('user.home', ['reg_employee' => $reg_employee,'ech' => $ech,'employee' => $employee,'zahtjevi_neodobreni' => $zahtjevi_neodobreni,'zahtjevi_odobreni' => $zahtjevi_odobreni,'ova_godina' => $ova_godina,'afterHours' => $afterHours,'questionnaires' => $questionnaires,'educations' => $educations, 'dataArr' => $dataArr, 'ads' => $ads, 'employee_tasks' => $employee_tasks, 'presentations' => $presentations,'catalog_manufacturers' => $catalog_manufacturers ]);
			} else if ( $temporary ) {
				return view('user.home', ['temporary' => $temporary]);

			} else {
				if(Sentinel::inRole('visitor')) {
					$message = session()->flash('success', 'Dobrodošao goste!');
					return redirect()->route('admin.visitors.index')->withFlashMessage($message);
				} else {
					return view('user.home');
				}				
			}			
		} else {
			return view('auth.login');
		}
    }
	
	public function show($slug)
	{
		//$post = Post::where('slug',$slug)->first();
		
		//return view('post.show')->with('post', $post);
	}
	
	public function storeComment(CommentRequest $request)
	{
		$post = Post::where('id', $request->post_id)->first();

		$user_1 = Sentinel::getUser();
		$user = Employee::where('employees.last_name',$user_1->last_name)->where('employees.first_name',$user_1->first_name)->first();
		
		$data = array(
			'user_id'  => $user->id,                 
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
					->subject('Odgovor na poruku');
			}
		);
		
		$message = session()->flash('success', 'You have successfully addad a new comment.');
		
		return redirect()->back()->withFlashMessage($message);
		//return redirect()->route('admin.posts.index')->withFlashMessage($message);
	}
	
}