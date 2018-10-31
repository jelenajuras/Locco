<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Registration;
use App\Models\VacationRequest;
use App\Models\AfterHour;
use App\Models\Kid;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DateTime;
use DateInterval;
use DatePeriod;

class GodisnjiController extends Controller
{
   // Računa broj neiskorištenih dana godišnjeg
   
   public function godišnji($user)
	{
		$registration = Registration::where('registrations.employee_id', $user->id)->first();
		
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
		$zahtjevi = VacationRequest::where('employee_id',$user->id)->get();
		
		/* ukupno iskorišteno godišnji zaposlenika*/
		$ukupnoGO = 0;
		$ukupnoGO_PG = 0;
		foreach($zahtjevi as $zahtjev){
			if($zahtjev->zahtjev == 'GO' & $zahtjev->odobreno == 'DA' ){
				$begin = new DateTime($zahtjev->GOpocetak);
				$end = new DateTime($zahtjev->GOzavršetak);
				$brojDana = date_diff($end, $begin);
				$end->setTime(0,0,1);
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($begin, $interval, $end);
				
				foreach ($period as $dan) {
					if(date_format($dan,'N') < 6 ){
						$ukupnoGO += 1;
						$ukupnoGO_PG += 1;
					}
					if(date_format($dan,'N') < 6 & date_format($dan,'d') == '01' & date_format($dan,'m') == '01' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '06' & date_format($dan,'m') == '01' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '01' & date_format($dan,'m') == '05' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '22' & date_format($dan,'m') == '06' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '25' & date_format($dan,'m') == '06' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '15' & date_format($dan,'m') == '08' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '05' & date_format($dan,'m') == '08' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '08' & date_format($dan,'m') == '10' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '01' & date_format($dan,'m') == '11' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '25' & date_format($dan,'m') == '12' ||
						date_format($dan,'N') < 6 & date_format($dan,'d') == '26' & date_format($dan,'m') == '12'){
							if(date_format($dan,'Y') == $ova_godina ){
								$ukupnoGO -= 1;
							} elseif(date_format($dan,'Y') == $prosla_godina ){
								$ukupnoGO_PG -= 1;
							}
					}
					if(date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018'){
						$ukupnoGO -= 1;
					}
				}
			}
		}
		
		return $dani_GO = $GO - $ukupnoGO;
	}
	
	// Računa broj dana između dva datuma
	
	public static function daniGO($zahtjev)
	{
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;
		
		$begin = new DateTime($zahtjev['GOpocetak']);
		$end = new DateTime($zahtjev['GOzavršetak']);
		$end->setTime(0,0,1);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		
		$ukupnoGO = 0;
		$ukupnoGO_PG = 0;
		
		foreach ($period as $dan) {
			if(date_format($dan,'N') < 6 ){
				$ukupnoGO += 1;
				$ukupnoGO_PG += 1;
			}
			if(date_format($dan,'d') == '01' & date_format($dan,'m') == '01' ||
				date_format($dan,'d') == '06' & date_format($dan,'m') == '01' ||
				date_format($dan,'d') == '01' & date_format($dan,'m') == '05' ||
				date_format($dan,'d') == '22' & date_format($dan,'m') == '06' ||
				date_format($dan,'d') == '25' & date_format($dan,'m') == '06' ||
				date_format($dan,'d') == '15' & date_format($dan,'m') == '08' ||
				date_format($dan,'d') == '05' & date_format($dan,'m') == '08' ||
				date_format($dan,'d') == '08' & date_format($dan,'m') == '10' ||
				date_format($dan,'d') == '01' & date_format($dan,'m') == '11' ||
				date_format($dan,'d') == '25' & date_format($dan,'m') == '12' ||
				date_format($dan,'d') == '26' & date_format($dan,'m') == '12'){
					if(date_format($dan,'Y') == $ova_godina ){
						$ukupnoGO -= 1;
					} elseif(date_format($dan,'Y') == $prosla_godina ){
						$ukupnoGO_PG -= 1;
					}
			}
			if(date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018' ||
				date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018'){
				$ukupnoGO -= 1;
			}
		}
				
		if($prosla_godina == '2017'){
			$GO_PG = 0;
			$ukupnoGO_PG = 0;
			$danaUk_PG = 0;
			$mjeseciUk_PG = 0;
			$godinaUk_PG = 0;
		}
		return $ukupnoGO;
	}
	
	// Računa broj dana između dva datuma sa vikendima i praznicima
	public static function ukupnoDani($zahtjev)
	{
		$begin = new DateTime($zahtjev['GOpocetak']);
		$end = new DateTime($zahtjev['GOzavršetak']);
		$end->setTime(0,0,1);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		
		$ukupnoDana = 0;
		
		foreach ($period as $dan) {
			$ukupnoDana += 1;
		}
		
		return $ukupnoDana;
	}
	
	/* računa broj slobodnih dana prema prekovremenim satima */
	public function slobodni_dani($user)
	{
		$registration = Registration::where('registrations.employee_id', $user->id)->first();
		$prekovremeniEmpl = AfterHour::where('employee_id',$registration->employee_id)->get();
		
		$razlika =0;
		foreach($prekovremeniEmpl as $prekovremeni){
			if($prekovremeni->odobreno == 'DA'){
				$vrijeme_1 = new DateTime($prekovremeni->vrijeme_od);  /* vrijeme od */
				$vrijeme_2 = new DateTime($prekovremeni->vrijeme_do);  /* vrijeme do */
				$razlika_vremena = $vrijeme_2->diff($vrijeme_1);  /* razlika_vremena*/
				$razlika += (int)$razlika_vremena->h;
			}
		}

		if($razlika >= 8){
			$razlika = round($razlika / 8, 0, PHP_ROUND_HALF_DOWN);
		} else {
			$razlika =0;
		}

		return $razlika;
	}
	
	/* računa iskorištene slobodne dane  - odobrene */
	public function koristeni_slobodni_dani($user)
	{
		$registration = Registration::where('registrations.employee_id', $user->id)->first();

		$sl_dani = VacationRequest::where('employee_id',$registration->employee_id)->where('zahtjev','SLD')->get();
		
		$dan = 0;
		foreach($sl_dani as $sl_dan){
			if($sl_dan->odobreno == 'DA'){
				$dan += 1;
			}
		}

		return $dan;
	}
	
	public function djeca($user){
		$kids = Kid::where('employee_id', $user->id)->get();
		$datum = new DateTime('now');    /* današnji dan */
		$danGOdijete = 0;
		
		foreach($kids as $kid){
			$datum_rodjenja = new DateTime($kid->datum_rodjenja);  /* datum rođenja djeteta */
			$godinaDijete = $datum_rodjenja->diff($datum); 
			if((int)$godinaDijete->y < 7){
				$djeca += 1;
			}
			if($djeca >= 2) {
				$danGOdijete = 1;
			}
		}
		return $danGOdijete;
	}
}