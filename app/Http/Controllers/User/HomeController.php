<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GodisnjiController;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\Employee_department;
use App\Models\VacationRequest;
use App\Models\EffectiveHour;
use App\Models\AfterHour;
use App\Models\Questionnaire;
use App\Models\Evaluation;
use App\Models\EvaluatingGroup;
use App\Models\EvaluatingQuestion;
use App\Models\Education;
use App\Models\Event;
use App\Models\EvaluationTarget;
use App\Models\Presentation;
use App\Models\Ad;
use App\Models\CatalogManufacturer;
use App\Models\CatalogCategory;
use Sentinel;
use DateTime;
use DatePeriod;
use DateInterval;

class HomeController extends GodisnjiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(Sentinel::check()) {
			$user = Sentinel::getUser();

			$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
			
			if($employee) {
				$employeeDepartments = Employee_department::where('employee_id', $employee->id)->get();
				
				$zahtjevi_neodobreni = VacationRequest::where('odobreno',null)->orderBy('GOpocetak','DESC')->get();
				$zahtjevi_odobreni = VacationRequest::where('odobreno','DA')->orderBy('GOpocetak','DESC')->get()->take(30);
				$afterHours = AfterHour::where('odobreno', null)->get();
	
				$reg_employee = Registration::where('registrations.employee_id', $employee->id)->first();
				$ech = EffectiveHour::where('employee_id', $employee->id)->first();
				$datum = new DateTime('now');    /* današnji dan */
				$ova_godina = date_format($datum,'Y');
				
				// ANKETE
				$questionnaires = Questionnaire::where('status','aktivna')->get();
				$evaluatingGroups = EvaluatingGroup::get();
				$evaluatingQuestions = EvaluatingQuestion::get();
				$evaluationTargets = EvaluationTarget::where('employee_id',$employee->id)->orderBy('created_at','DESC')->get();
				$evaluations = Evaluation::where('employee_id',$employee->id)->get();
				
				// Edukacija
				$educations = Education::where('status','aktivna')->get();
				$presentations = Presentation::where('status','aktivan')->get();
				$ads = Ad::get();
	
				// Proizvođači opreme
				$catalog_manufacturers = CatalogManufacturer::get()->count();
				//$catalog_categories = CatalogCategory::get()->count();
			
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
	
				$absences = VacationRequest::where('odobreno','DA')->whereYear('GOpocetak', $god_select)->get();
				$absences = $absences->merge(VacationRequest::where('odobreno','DA')->whereYear('GOzavršetak', $god_select)->get());
				foreach($absences as $absence) {
					$begin = new DateTime($absence->GOpocetak);
					$end = new DateTime($absence->GOzavršetak);
					$end->setTime(0,0,1);
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);
					foreach ($period as $dan) {
						if(date_format($dan,'Y') >= $god_select) {  // ako je trenutna godina
							if($absence->zahtjev == 'Izlazak') {
								array_push($dataArr, ['name' => 'absence', 'type' => $absence->zahtjev, 'date' => date_format($dan,'Y-m-d'), 'employee' => $absence->employee['first_name'] . ' ' . $absence->employee['last_name'], 'time' => date( 'G:i',(strtotime($absence->vrijeme_od))) . '-' . date( 'G:i',(strtotime($absence->vrijeme_do))) ]);
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
	
				return view('user.home', ['reg_employee' => $reg_employee,'ech' => $ech,'employee' => $employee,'zahtjevi_neodobreni' => $zahtjevi_neodobreni,'zahtjevi_odobreni' => $zahtjevi_odobreni,'ova_godina' => $ova_godina,'afterHours' => $afterHours,'questionnaires' => $questionnaires, 'evaluatingGroups' => $evaluatingGroups, 'evaluatingQuestions' => $evaluatingQuestions, 'educations' => $educations, 'evaluationTargets' => $evaluationTargets, 'evaluations' => $evaluations, 'dataArr' => $dataArr, 'ads' => $ads, 'presentations' => $presentations, 'employeeDepartments' => $employeeDepartments, 'catalog_manufacturers' => $catalog_manufacturers ]);
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
}
