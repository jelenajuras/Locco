<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\VacationRequest;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Work;
Use Mail;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Http\Controllers\GodisnjiController;

class GO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:GO';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Izostanci';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$datum = new DateTime('now');
		date_modify($datum, '+1day');
		$dan = date_format($datum,'d');
		$mjesec = date_format($datum,'m');
		$ova_godina = date_format($datum,'Y');

		$dan_izostanci = array();
		
		$izostanci = VacationRequest::join('employees','vacation_requests.employee_id', '=', 'employees.id')->select('vacation_requests.*', 'employees.first_name','employees.last_name')->orderBy('vacation_requests.zahtjev','ASC')->orderBy('employees.last_name','ASC')->get();

		foreach($izostanci as $izostanak){
			if($izostanak->odobreno == 'DA'){
				$begin1 = new DateTime($izostanak->GOpocetak);
				$end1 = new DateTime($izostanak->GOzavršetak);
				$end1->setTime(0,0,1);
				$interval1 = DateInterval::createFromDateString('1 day');
				$period1 = new DatePeriod($begin1, $interval1, $end1);
				
				$begin_dan = date_format($begin1,'d');
				$begin_mjesec = date_format($begin1,'m');			
				$begin_godina = date_format($begin1,'Y');	
				if($izostanak->zahtjev == 'Izlazak'){
					if($begin_dan == $dan && $begin_mjesec == $mjesec  && $begin_godina == $ova_godina){
						array_push($dan_izostanci,array('ime' => $izostanak->first_name . ' ' . $izostanak->last_name, 'zahtjev' =>  $izostanak->zahtjev, 'period' => date('d.m.Y', strtotime($izostanak->GOpocetak)) . ' - ' .  date('d.m.Y', strtotime($izostanak->GOzavršetak)), 'vrijeme' => $izostanak->vrijeme_od . ' - ' .  $izostanak->vrijeme_do,  'napomena' =>  $izostanak->napomena, 'GO' => '', 'ukupnoGO' => ''));
					}
				} else {
					$registration = Registration::where('registrations.employee_id', $izostanak->employee_id)->first();
					
					/* izračun dana GO */
					$razmjeranGO = GodisnjiController::razmjeranGO($registration);
					$daniZahtjevi = GodisnjiController::daniZahtjevi($registration);
					
					$razmjeranGO_PG = GodisnjiController::razmjeranGO_PG($registration);
					$daniZahtjevi_PG = GodisnjiController::daniZahtjeviPG($registration);
					
					$dani_GO = $razmjeranGO + $razmjeranGO_PG - $daniZahtjevi - $daniZahtjevi_PG ;
		
					if($begin1 == $end1 && $begin_dan == $dan && $begin_mjesec == $mjesec){
						array_push($dan_izostanci,array('ime' => $izostanak->first_name . ' ' . $izostanak->last_name, 'zahtjev' =>  $izostanak->zahtjev, 'period' => date('d.m.Y', strtotime( $izostanak->GOpocetak)), 'vrijeme' => $izostanak->vrijeme_od . ' - ' .  $izostanak->vrijeme_do, 'dani_GO' => $dani_GO, 'napomena' =>  $izostanak->napomena ));
					} else {
						foreach ($period1 as $dan1) {  //ako je dan  GO !!!
							$period_day = date_format($dan1,'d');
							$period_month = date_format($dan1,'m');
							$period_year = date_format($dan1,'Y');
							if($period_day == $dan & $period_month == $mjesec & $period_year == $ova_godina || $begin1 == $end1 ){
								array_push($dan_izostanci,array('ime' => $izostanak->first_name . ' ' . $izostanak->last_name, 'zahtjev' =>  $izostanak->zahtjev, 'period' => date('d.m.Y', strtotime( $izostanak->GOpocetak)) . ' - ' .  date('d.m.Y', strtotime($izostanak->GOzavršetak)), 'vrijeme' => $izostanak->vrijeme_od . ' - ' .  $izostanak->vrijeme_do, 'napomena' =>  $izostanak->napomena, 'dani_GO' => $dani_GO));
							}
						}
					}
				}
			}
		}
	
		// Send the email to users
		$send_to = array('uprava@duplico.hr','jelena.juras@duplico.hr','matija.barberic@duplico.hr');
		$uprava = array('zeljko.rendulic@duplico.hr','durdica.rendulic@duplico.hr','ivan.rendulic@duplico.hr','nikola.rendulic@duplico.hr','matija.rendulic@duplico.hr');
		$dep_mails = array();
		$departments = Department::get();
		$works = Work::get();
		foreach ($departments as $department) {
			array_push($dep_mails, $department->employee['email']);
		}

		foreach ($works as $work) {
			array_push($dep_mails, $work->prvi_nadredjeni['email']);
		}

		$dep_mails = array_filter(array_unique($dep_mails),'strlen');
		$diff = array_diff($dep_mails, $uprava );
		foreach ($diff as $add_mail) {
			array_push($send_to, $add_mail);
		}
		foreach (array_unique($send_to) as $send_to_mail) {
			Mail::queue('email.GO', ['dan_izostanci' => $dan_izostanci, 'datum' =>  date_format($datum,'d.m.Y') ], function ($mail) use ($send_to_mail , $datum) {
				$mail->to( $send_to_mail )
					->from('info@duplico.hr', 'Duplico')
					->subject('Izostanci ' . ' djelatnika -' . date_format($datum,'d.m.Y'));
			});
		}
		
		$this->info('GO messages sent successfully!');
	}	
}