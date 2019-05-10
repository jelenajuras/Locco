<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\VacationRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Models\AfterHour;
use Sentinel;
use DateTime;
use DateInterval;
use DatePeriod;

class DashboardController extends Controller
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
/* Staž prijašnji */
		
		$stažY = 0;
		$stažM = 0;
		$stažD = 0;
		if($registration->staz) {
			$staž = $registration->staz;
			$staž = explode('-',$registration->staz);
			$stažY = $staž[0];
			$stažM = $staž[1];
			$stažD = $staž[2];
		}
		
/* Staž Duplico */	
		$stažDuplico = 0;
		$datum = new DateTime('now');    /* današnji dan */
		$datum_1 = new DateTime($registration->datum_prijave);  /* datum prijave */
		$stažDuplico = $datum_1->diff($datum);  /* staž u Duplicu*/

		$godina = $stažDuplico->format('%y');  
		$mjeseci = $stažDuplico->format('%m');
		$dana = $stažDuplico->format('%d');
		
/* Staž ukupan */
		$danaUk=0;
		$mjeseciUk=0;
		$godinaUk=0;
		
		if(($dana+$stažD) > 30){
			$danaUk = ($dana+$stažD) -30;
			$mjeseciUk = 1;
		}else {
			$danaUk = ($dana+$stažD);
		}
		
		if(($mjeseci+$stažM) > 12){
			$mjeseciUk += ($mjeseci+$stažM) -12;
			$godinaUk = 1;
		}else {
			$mjeseciUk += ($mjeseci+$stažM);
		}
		$godinaUk += ($godina + $stažY);

	
/* Godišnji odmor - dani*/
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;
		
		$GO = 20;
		$GO += (int)($godinaUk/ 4) ;
		
		If($GO > 25){
			$GO = 25;
		} else {
			$GO = $GO;
		}
		
/* Staž prošle godine */
		$datumPG = new DateTime($prosla_godina .'-12-31');    /* zadnji dan prošle godine */
		$godina_prijave = date_format($datum_1,'Y');  /* godina prijave */
		
		$danaPG = 0;
		$mjeseciPG =0;
		$godinaPG = 0;
		$danaUk_PG=0;
		$mjeseciUk_PG=0;
		$godinaUk_PG=0;
			
		if($godina_prijave < $ova_godina){
			$stažDuplicoPG = $datum_1->diff($datumPG);  /* staž u Duplicu do 31.12*/
			$godinaPG = $stažDuplicoPG->format('%y');  
			$mjeseciPG = $stažDuplicoPG->format('%m');
			$danaPG = $stažDuplicoPG->format('%d');
		}
		
/* Staž ukupan do 31.12.*/
			if(($danaPG+$stažD) > 30){
				$danaUk_PG = ($danaPG+$stažD) -30;
				$mjeseciUk_PG = 1;
			} else {
				$danaUk_PG = ($danaPG+$stažD);
			}
			
			if(($mjeseciPG+$stažM) > 12){
				$mjeseciUk_PG += ($mjeseciPG+$stažM) -12;
				$godinaUk_PG = 1;
			} else {
				$mjeseciUk_PG += ($mjeseciPG+$stažM);
			}
			$godinaUk_PG += ($godinaPG + $stažY);
			$GO_PG = 20;
			$GO_PG += (int)($godinaUk_PG/ 4) ;
		
			If($GO_PG > 25){
				$GO_PG = 25;
			} else {
				$GO_PG = $GO_PG;
			}

/* Zahtjevi */		
		$zahtjevi = VacationRequest::where('employee_id',$employee->id)->get();
/* ukupno iskorišteno godišnji zaposlenika*/
		$ukupnoGO =0;
		$ukupnoGO_PG = 0;
		foreach($zahtjevi as $zahtjev){
			if($zahtjev->zahtjev == 'GO' & $zahtjev->odobreno == 'DA' ){
				$begin = new DateTime($zahtjev->GOpocetak);
				$end = new DateTime($zahtjev->GOzavršetak);
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($begin, $interval, $end);
				if(date_format($end,'Y') == $ova_godina){
					$ukupnoGO +=1;
				}
				if(date_format($end,'Y') == $prosla_godina){
					$ukupnoGO_PG +=1;
				}
				foreach ($period as $dan) {
					if(date_format($dan,'N') < 6 & date_format($dan,'Y') == $ova_godina){
						$ukupnoGO += 1;
					}
					if(date_format($dan,'N') < 6 & date_format($dan,'Y') == $prosla_godina){
						$ukupnoGO_PG += 1;
					}
					if(date_format($dan,'d') == '01' & date_format($dan,'m') == '01' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '06' & date_format($dan,'m') == '01' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '15' & date_format($dan,'m') == '08' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '05' & date_format($dan,'m') == '08' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '15' & date_format($dan,'m') == '08' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018'){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '01' & date_format($dan,'m') == '05' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018'){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '22' & date_format($dan,'m') == '06' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '25' & date_format($dan,'m') == '06' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '08' & date_format($dan,'m') == '10' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '01' & date_format($dan,'m') == '11' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '25' & date_format($dan,'m') == '12' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					if(date_format($dan,'d') == '26' & date_format($dan,'m') == '12' ){
						$ukupnoGO -= 1;
						$ukupnoGO_PG -= 1;
					}
					
				}
			}
		}
		
		if($prosla_godina == '2017'){
			$GO_PG = 0;
			$ukupnoGO_PG = 0;
			$danaUk_PG = 0;
			$mjeseciUk_PG = 0;
			$godinaUk_PG = 0;
		}

		$zahtjeviD = VacationRequest::orderBy('GOpocetak','DESC')->take(30)->get();
		
		$posts = Post::where('to_employee_id',$employee->id)->take(5)->get();
		$posts2 = Post::where('employee_id',$user->id)->take(5)->get();
		
		return view('user.home')->with('user', $user)->with('registration', $registration)->with('employee', $employee)->with('stažY', $stažY)->with('stažM', $stažM)->with('stažD', $stažD)->with('godina', $godina)->with('mjeseci', $mjeseci)->with('dana', $dana)->with('danaUk', $danaUk)->with('mjeseciUk', $mjeseciUk)->with('godinaUk', $godinaUk)->with('danaUk_PG', $danaUk_PG)->with('mjeseciUk_PG', $mjeseciUk_PG)->with('godinaUk_PG', $godinaUk_PG)->with('GO', $GO)->with('GO_PG', $GO_PG)->with('zahtjevi', $zahtjevi)->with('zahtjeviD', $zahtjeviD)->with('ukupnoGO', $ukupnoGO)->with('ukupnoGO_PG', $ukupnoGO_PG)->with('ova_godina', $ova_godina)->with('prosla_godina', $prosla_godina)->with('posts', $posts)->with('posts2', $posts2)->with('comments', $comments)->with('afterHours', $afterHours);
    }
}
