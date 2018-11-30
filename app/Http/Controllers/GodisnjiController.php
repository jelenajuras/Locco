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
   // Računa broj neiskorištenih dana godišnjeg ova godina
   public static function godisnji($user)
	{
		$registration = Registration::where('registrations.employee_id', $user->id)->first();
		
		/* staz prijašnji */
		$stazY = 0;
		$stazM = 0;
		$stazD = 0;
		if($registration->staz) {
			$staz = $registration->staz;
			$staz = explode('-',$registration->staz);
			$stazY = $staz[0];
			$stazM = $staz[1];
			$stazD = $staz[2];
		}
		
/* staz Duplico */	
		$stazDuplico = 0;
		$datum = new DateTime('now');    /* današnji dan */
		$datum_1 = new DateTime($registration->datum_prijave);  /* datum prijave */
		$stazDuplico = $datum_1->diff($datum);  /* staz u Duplicu*/

		$godina = $stazDuplico->format('%y');  
		$mjeseci = $stazDuplico->format('%m');
		$dana = $stazDuplico->format('%d');
		
/* staz ukupan */
		$danaUk=0;
		$mjeseciUk=0;
		$godinaUk=0;
		
		if(($dana+$stazD) > 30){
			$danaUk = ($dana+$stazD) -30;
			$mjeseciUk = 1;
		}else {
			$danaUk = ($dana+$stazD);
		}
		
		if(($mjeseci+$stazM) > 12){
			$mjeseciUk += ($mjeseci+$stazM) -12;
			$godinaUk = 1;
		}else {
			$mjeseciUk += ($mjeseci+$stazM);
		}
		$godinaUk += ($godina + $stazY);

	
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
		
/* staz prošle godine */
		$datumPG = new DateTime($prosla_godina .'-12-31');    /* zadnji dan prošle godine */
		$godina_prijave = date_format($datum_1,'Y');  /* godina prijave */
		
		$danaPG = 0;
		$mjeseciPG =0;
		$godinaPG = 0;
		$danaUk_PG=0;
		$mjeseciUk_PG=0;
		$godinaUk_PG=0;
			
		if($godina_prijave < $ova_godina){
			$stazDuplicoPG = $datum_1->diff($datumPG);  /* staz u Duplicu do 31.12*/
			$godinaPG = $stazDuplicoPG->format('%y');  
			$mjeseciPG = $stazDuplicoPG->format('%m');
			$danaPG = $stazDuplicoPG->format('%d');
		}
		
/* staz ukupan do 31.12.*/
			if(($danaPG+$stazD) > 30){
				$danaUk_PG = ($danaPG+$stazD) -30;
				$mjeseciUk_PG = 1;
			} else {
				$danaUk_PG = ($danaPG+$stazD);
			}
			
			if(($mjeseciPG+$stazM) > 12){
				$mjeseciUk_PG += ($mjeseciPG+$stazM) -12;
				$godinaUk_PG = 1;
			} else {
				$mjeseciUk_PG += ($mjeseciPG+$stazM);
			}
			$godinaUk_PG += ($godinaPG + $stazY);
			$GO_PG = 20;
			$GO_PG += (int)($godinaUk_PG/ 4) ;
		
			If($GO_PG > 25){
				$GO_PG = 25;
			} else {
				$GO_PG = $GO_PG;
			}

/* Zahtjevi ova godina */		
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
						date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '22' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '20' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '13' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2020' ||
						date_format($dan,'d') == '11' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2020'){
						$ukupnoGO -= 1;
					}
				}
			}
		}
		
		return $dani_GO = $GO - $ukupnoGO;
		
	}

	// Računa broj dana godišnjeg ova godina              /************ RADI!!!!!!! ***************/
	public static function godisnjiUser($user)
	{
		$registration = $user;
		
/* staz prijašnji */
		$stazY = 0;
		$stazM = 0;
		$stazD = 0;
		if($registration->staz) {
			$staz = $registration->staz;
			$staz = explode('-',$registration->staz);
			$stazY = $staz[0];
			$stazM = $staz[1];
			$stazD = $staz[2];
		}
		
/* staz Duplico */	
		$stazDuplico = 0;
		$datum = new DateTime('now');    /* današnji dan */
		$datum_1 = new DateTime($registration->datum_prijave);  /* datum prijave */
		$stazDuplico = $datum_1->diff($datum);  /* staz u Duplicu*/

		$godina = $stazDuplico->format('%y');  
		$mjeseci = $stazDuplico->format('%m');
		$dana = $stazDuplico->format('%d');
		
/* staz ukupan */
		$danaUk=0;
		$mjeseciUk=0;
		$godinaUk=0;
		
		if(($dana+$stazD) > 30){
			$danaUk = ($dana+$stazD) -30;
			$mjeseciUk = 1;
		}else {
			$danaUk = ($dana+$stazD);
		}
		
		if(($mjeseci+$stazM) > 12){
			$mjeseciUk += ($mjeseci+$stazM) -12;
			$godinaUk = 1;
		}else {
			$mjeseciUk += ($mjeseci+$stazM);
		}
		$godinaUk += ($godina + $stazY);
	
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

		return $GO;
	}

	//računa iskorištene dane godišnjeg odmora ova godina  /************ RADI!!!!!!! ***************/
	public static function daniZahtjevi($user)
	{
		/* Zahtjevi ova godina */		
		$zahtjevi = VacationRequest::where('employee_id',$user->employee_id)->get();
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;

		/* ukupno iskorišteno godišnji zaposlenika*/
		$ukupnoGO = 0;
		$ukupnoGO_PG = 0;
		foreach($zahtjevi as $zahtjev){
			if($zahtjev->zahtjev == 'GO' && $zahtjev->odobreno == 'DA' ){
				$begin = new DateTime($zahtjev->GOpocetak);
				$end = new DateTime($zahtjev->GOzavršetak);
				$end->setTime(0,0,1);
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($begin, $interval, $end);
				foreach ($period as $dan) {
					if(date_format($dan,'N') < 6 ){
						if(date_format($dan,'Y') == $ova_godina ){
							$ukupnoGO += 1;
						}
					}
					if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
						date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '15' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '05' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '08' && date_format($dan,'m') == '10' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '11' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '26' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '22' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '20' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '13' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2020' ||
						date_format($dan,'d') == '11' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2020'){
							if(date_format($dan,'Y') == $ova_godina ){
								$ukupnoGO -= 1;
							}
					}
				}	
			}
		}
		return $ukupnoGO;
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
				date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018' ||
				date_format($dan,'d') == '22' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2019' ||
				date_format($dan,'d') == '20' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2019' ||
				date_format($dan,'d') == '13' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2020' ||
				date_format($dan,'d') == '11' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2020'){
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
	
	/* računa broj slobodnih dana prema prekovremenim satima */   /************ RADI!!!!!!! ***************/
	public static function slobodni_dani($user)
	{
		// $user = $registration
		// $registration = Registration::where('registrations.employee_id', $user->id)->first();
		$prekovremeniEmpl = AfterHour::where('employee_id',$user->employee_id)->get();
		
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
	
	/* računa iskorištene slobodne dane  - odobrene */          /************ RADI!!!!!!! ***************/
	public static function koristeni_slobodni_dani($user)
	{
		// $user = $registration
		//$registration = Registration::where('registrations.employee_id', $user->id)->first();

		$sl_dani = VacationRequest::where('employee_id',$user->employee_id)->where('zahtjev','SLD')->get();
		
		$SLdan = 0;
		foreach($sl_dani as $sl_dan){
			if($sl_dan->odobreno == 'DA'){
				$begin = new DateTime($sl_dan->GOpocetak);
				$end = new DateTime($sl_dan->GOzavršetak);
				$end->setTime(0,0,1);
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($begin, $interval, $end);
				foreach ($period as $dan) {
					if(date_format($dan,'N') < 6 ){
						$SLdan += 1;
					}
					if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
						date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '15' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '05' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '08' && date_format($dan,'m') == '10' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '11' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '26' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '22' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '20' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '13' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2020' ||
						date_format($dan,'d') == '11' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2020'){
							if(date_format($dan,'Y') == $ova_godina ){
								$SLdan -= 1;
							}
					}
				}
				
			}
		}

		return $SLdan;
	}
	
	/* Računa dane GO za djecu*/	
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
	
	/* Računa staz u Duplicu */	                /************ RADI!!!!!!! ***************/
	public static function stazDuplico($user)
	{
		$datum = new DateTime('now');    /* današnji dan */
		$stazDuplico = 0;
		$datum_1 = new DateTime($user->datum_prijave);  /* datum prijave - registracija */
		
		$stazDuplico = $datum_1->diff($datum);  /* staz u Duplicu*/
		
		return $stazDuplico;
	}
	
	/* Računa ukupan staž */	
	public static function stazUkupno($user)   /************ RADI!!!!!!! ***************/
	{
		
		$stazDuplico = GodisnjiController::stazDuplico($user);
		$godina = $stazDuplico->format('%y');  
		$mjeseci = $stazDuplico->format('%m');
		$dana = $stazDuplico->format('%d');
		
		$stazY = 0;
		$stazM = 0;
		$stazD = 0;
		if($user->staz) {
			$stazPrijasnji = $user->staz;
			$stazPrijasnji = explode('-',$user->staz);
			$stazY = $stazPrijasnji[0];
			$stazM = $stazPrijasnji[1];
			$stazD = $stazPrijasnji[2];
		} 
		
		/* Staž ukupan */
		$danaUk=0;
		$mjeseciUk=0;
		$godinaUk=0;
		
		if(($dana+$stazD) > 30){
			$danaUk = ($dana+$stazD) -30;
			$mjeseciUk = 1;
		}else {
			$danaUk = ($dana+$stazD);
		}
		
		if(($mjeseci+$stazM) > 12){
			$mjeseciUk += ($mjeseci+$stazM) -12;
			$godinaUk = 1;
		}else {
			$mjeseciUk += ($mjeseci+$stazM);
		}
		$godinaUk += ($godina + $stazY);
								
		$staz = array($godinaUk,$mjeseciUk,$danaUk);
		
		return $staz;
	}

	/*  računa razmjeran GO */
	public static function razmjeranGO($user)
	{
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$ovaj_mjesec = date_format($datum,'m');
		$ovaj_dan = date_format($datum,'d');
		if($ovaj_dan >= 15){
			$ovaj_mjesec +=1;
		}
		$GO  = GodisnjiController::godisnjiUser($user);

		if($user->datum_prijave) {
			$datum_prijave = $user->datum_prijave;
			$datum_prijave = explode('-',$user->datum_prijave);
			$prijavaGodina = $datum_prijave[0];
			$prijava = new DateTime($user->datum_prijave);
			$staz = $prijava->diff($datum);   /* staz u Duplicu*/
			$mjesec = $staz->format('%m');
			$dan = $staz->format('%d');
			if($dan >= 15){
				$mjesec +=1;
			}
			if($prijavaGodina < $ova_godina){
				$razmjeranGO = round($GO/12 * $ovaj_mjesec, 0, PHP_ROUND_HALF_UP);
			} else {
				if($user->prekidStaza == 'DA' || $user->prvoZaposlenje == 'DA'){
					if($mjesec >= 6){
						$razmjeranGO = $GO;
					} else {
						$razmjeranGO = round($GO/12 * $mjesec, 0, PHP_ROUND_HALF_UP);
					}
				} else {
					$razmjeranGO = round($GO/12 * $mjesec, 0, PHP_ROUND_HALF_UP);
				}
			}
			
		} else {
			$razmjeranGO = 0;
		}
		return $razmjeranGO;
	}
}