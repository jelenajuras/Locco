<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\VacationRequest;
use App\Http\Requests\Vacation_RequestRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GodisnjiController;
use Sentinel;
use App\Models\Employee;
use App\Models\Kid;
use App\Models\Registration;
use App\Models\EmployeeTermination;
use App\Models\Work;
use App\Models\AfterHour;
use Mail;
use Activation;
use DateTime;
use DateInterval;
use DatePeriod;
use Validator;

class VacationRequestController extends GodisnjiController
{
    /**
   *
   * Set middleware to quard controller.
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
		$prekovremeni = AfterHour::get();
		
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;
		$godine = GodisnjiController::godineZahtjeva();
	
		if(Sentinel::inRole('administrator')){
			$requests = VacationRequest::orderBy('created_at','DESC')->get();
			$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		} else {
			$requests = VacationRequest::where('employee_id',$employee->id)->orderBy('created_at','DESC')->get();
			$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->where('employee_id',$employee->id)->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		}

		return view('admin.vacation_requests.index',['registrations'=>$registrations, 'requests'=>$requests, 'prekovremeni'=>$prekovremeni, 'ova_godina'=>$ova_godina, 'prosla_godina'=>$prosla_godina,'godine'=>$godine]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$user = Sentinel::getUser();
		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$registration = Registration::where('registrations.employee_id', $employee->id)->first();
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		//	$razmjeranGO = $this->razmjeranGO($registration);
		//	$razmjeranGO_PG = $this->razmjeranGO_PG($registration);
		//	$daniZahtjevi = $this->daniZahtjevi($registration);
		//	$daniZahtjevi_PG = $this->daniZahtjeviPG($registration);
		//	$preostali_dani = $this->zahtjevi_novo($registration)['preostalo_PG'] + $this->zahtjevi_novo($registration)['preostalo_OG'];

		$godineZahtjeva = $this->godineZahtjeva();
		$GO_dani = 0;
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');

		foreach ($godineZahtjeva as $godina) {
			if( $godina == $ova_godina) {
				$GO_dani += GodisnjiController::razmjeranGO($registration); // razmjerni dani ova godina
			
			} else {
				$GO_dani += GodisnjiController::razmjeranGO_PG($registration, $godina); // razmjerni dani za sve godine
			}
		}
	
		$sviZahtjeviZaGodine = $this->zahtjeviSveGodine($registration);
		$brojDanaZahtjeva = 0;
		foreach($sviZahtjeviZaGodine  as $zahtjevi) {
			$brojDanaZahtjeva += count($zahtjevi); 
		}
	
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($registration);
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($registration);

		$preostali_dani = $GO_dani - $brojDanaZahtjeva;

		//return view('admin.vacation_requests.create')->with('registration', $registration)->with('registrations', $registrations)->with('employee', $employee)->with('daniZahtjevi', $daniZahtjevi)->with('daniZahtjevi_PG', $daniZahtjevi_PG)->with('slobodni_dani', $slobodni_dani )->with('koristeni_slobodni_dani', $koristeni_slobodni_dani)->with('razmjeranGO', $razmjeranGO)->with('razmjeranGO_PG', $razmjeranGO_PG );
		//	return view('admin.vacation_requests.create', ['registration' => $registration, 'registrations' => $registrations, 'employee' => $employee, 'preostali_dani' => $preostali_dani, 'slobodni_dani' => $slobodni_dani , 'koristeni_slobodni_dani' => $koristeni_slobodni_dani   ]);
		return view('admin.vacation_requests.create', ['registration' => $registration, 'registrations' => $registrations, 'employee' => $employee, 'preostali_dani' => $preostali_dani, 'slobodni_dani' => $slobodni_dani , 'koristeni_slobodni_dani' => $koristeni_slobodni_dani   ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Vacation_RequestRequest $request)
    {
		$input = $request->except(['_token']);

		if($input['end_date'] == '' ){
			$input['end_date'] = $input['start_date'];
		}

		if($input['employee_id'] == 'svi'){
			$registrations = Registration::where('odjava', null)->get();    /* svi prijavljeni djelatnici */										/* AKO JE ZAHTJEV NA SVE DJELATNIKE */
			foreach($registrations as $registration){
					$data = array(
						'zahtjev'  			=> $input['zahtjev'],
						'employee_id'  		=> $registration->employee_id,
						'start_date'    		=> date("Y-m-d", strtotime($input['start_date'])),
						'end_date'		=> date("Y-m-d", strtotime($input['end_date'])),
						'start_time'  		=> $input['start_time'],
						'end_time'  		=> $input['end_time'],
						'odobreno' 			=> 'DA',
						'odobrio_id' 		=> '58',
						'napomena'  		=> $input['napomena']
					);
					$vacationRequest = new VacationRequest();
					$vacationRequest->saveVacationRequest($data);
			}
		} elseif(is_array($input['employee_id']) && count($input['employee_id'])>0) {   /* ZAHTJEV NA VIŠE DJELATNIKA */
			foreach($input['employee_id'] as $employee_id){
				$data = array(
				'zahtjev'  			=> $input['zahtjev'],
				'employee_id'  		=> $employee_id,
				'start_date'    		=> date("Y-m-d", strtotime($input['start_date'])),
				'end_date'		=> date("Y-m-d", strtotime($input['end_date'])),
				'start_time'  		=> $input['start_time'],
				'end_time'  		=> $input['end_time'],
				'napomena'  		=> $input['napomena'],
				'odobreno' 			=> null,   // ODOBRENO ILI NE????
				'odobrio_id' 		=> null
				);
				if($input['zahtjev'] == 'Bolovanje'){
					$data += ['odobreno' => 'DA'];
				}
				$vacationRequest = new VacationRequest();
				$vacationRequest->saveVacationRequest($data);

				$user = Registration::where('employee_id', $employee_id )->first(); 	/* djelatnik */
				$employee_mail = $user->employee['email'];										/* mail djelatnika */

				if($input['zahtjev'] == 'GO'){
					$zahtjev2 = 'korištenje godišnjeg odmora';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'COVID-19'){
					$zahtjev2 = 'oslobođenje od rada-COVID-19';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'RD'){
					$zahtjev2 = 'rad od doma';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'Izlazak') {
					$zahtjev2 = 'prijevremeni izlaz';
					$vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
				} elseif($input['zahtjev'] == 'Bolovanje'){
					$zahtjev2 = 'bolovanje';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'SLD'){
					$zahtjev2 = 'slobodan dan';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'NPL'){
					$zahtjev2 = 'neplaćeni dopust';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'PL'){
					$zahtjev2 = 'plaćeni dopust';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'VIK'){
					$zahtjev2 = 'slobodan vikend';
					$vrijeme="";
				} elseif($input['zahtjev'] == 'CEK'){
					$zahtjev2 = 'čekanje';
					$vrijeme="";
				}
				
				$employee = Employee::where('employees.id',$user->employee_id)->first();
				
				if( $input['email'] == 'DA' ) {
					$work = Work::where('id', $user->radnoMjesto_id)->first();   //radno mjesto zaposlenika
					// $department = $work->department; 							// odjel kojem pripada radno mjesto
					// $department_nadredjeni = $department->employee; 			 // nadređeni odjela
					$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta
					$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
					$registration_superior = $user->superior;   				// nadređeni djelatnik - Registration
					$employee_departments = array();

					foreach ( $employee->departments as $employee_department) {
						array_push($employee_departments,$employee_department->department['name']);
					}
					if($work_voditelj) {
						$mail_work_voditelj = $work_voditelj->email;
					} elseif($work_nadredjeni) {
						$mail_work_voditelj = $work_nadredjeni->email;	
					}

					$zahtjev = array('start_date' =>$input['start_date'], 'end_date' =>$input['end_date']);  // array zatjev početak - kraj
					$dani_zahtjev = $this->daniGO($zahtjev); 										//vraća broj dana zahtjeva
					
					//slobodni dani
					$slobodni_dani = $this->prekovremeni_bez_izlazaka($user); /* računa broj slobodnih dana prema prekovremenim satima */
					$koristeni_slobodni_dani = $this->koristeni_slobodni_dani($user);			//* računa iskorištene slobodne dane */
					$ukupnoDani = $this->ukupnoDani($zahtjev); //vraća dane zahtjeva
					$razlika_SLD = $slobodni_dani - $koristeni_slobodni_dani - $ukupnoDani;

					/*** Nova kalkulacija preostalih DANA */
					$godineZahtjeva = $this->godineZahtjeva();
					$dani_GO = 0;
					$datum = new DateTime('now');    /* današnji dan */
					$ova_godina = date_format($datum,'Y');
			
					foreach ($godineZahtjeva as $godina) {
						if( $godina == $ova_godina) {
							$dani_GO += GodisnjiController::razmjeranGO($user); 			// razmjerni dani ova godina
						} else {
							$dani_GO += GodisnjiController::razmjeranGO_PG($user, $godina); // razmjerni dani za sve godine
						}
					}
				
					$sviZahtjeviZaGodine = $this->zahtjeviSveGodine($user);
					$brojDanaZahtjeva = 0;
					foreach($sviZahtjeviZaGodine  as $zahtjevi) {
						$brojDanaZahtjeva += count($zahtjevi);
					}

					$razlika_dana = $dani_GO - $brojDanaZahtjeva;      // razlika razmjerni dani - dani zahtjevi

					$ja = 'jelena.juras@duplico.hr';
					try {
						if(in_array('Radiona',$employee_departments) && $input['zahtjev'] == 'Izlazak') {
							$send_to = 'borislav.peklic@duplico.hr';
						//	$send_to = 'jelena.juras@duplico.hr';

							Mail::queue(
								'email.zahtjevGO',
								['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
								function ($message) use ($send_to, $employee) {
									$message->to($send_to)
										->from('info@duplico.hr', 'Duplico')
										->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
								}
							);
						} else {
							if(isset($mail_work_voditelj)){
								if($input['zahtjev'] == 'Bolovanje'){ 			// ako je bolovanje
									Mail::queue(
										'email.zahtjevGO',
										['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
										function ($message) use ($ja, $employee) {
											$message->to('matija.barberic@duplico.hr')
													->from('info@duplico.hr', 'Duplico')
													->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
										}
									);
									Mail::queue(
										'email.zahtjevGO',
										['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
										function ($message) use ($mail_work_voditelj, $employee) {
											$message->to($mail_work_voditelj)
													->from('info@duplico.hr', 'Duplico')
													->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
										}
									);
								} else {										// svi ostali zahtjevi
									if($mail_work_voditelj != '' || $mail_work_voditelj != null) {
										Mail::queue(
											'email.zahtjevGO',
											['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
											function ($message) use ($mail_work_voditelj, $employee) {
												$message->to($mail_work_voditelj)
													->from('info@duplico.hr', 'Duplico')
													->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
											}
										);
										Mail::queue(
											'email.zahtjevGO',
											['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
											function ($message) use ($ja, $employee) {
												$message->to($ja)
													->from('info@duplico.hr', 'Duplico')
													->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
											}
										);
									}
								}
							}
						}

						
					
					} catch (Exception $e) {
						$message = session()->flash('error', 'Mail nije poslan, ' . $e->getMessage());
						return redirect()->back()->withFlashMessage($message);
					}
				}

			}
		} else {																			/* ZAHTJEV NA JEDNOG DJELATNIKA*/
			$user = Registration::where('employee_id', $input['employee_id'] )->first(); 	/* djelatnik */
			$employee_mail = $user->employee['email'];										/* mail djelatnika */

			$request_exist = GodisnjiController::go_for_request($input['employee_id'], $input['start_date'], $input['end_date'] );
		
			if($request_exist == false) {
				$message = session()->flash('error', 'Zahtjev za dan već postoji, nije moguće poslati zahtjev');     /* AKO ZAHTJEV VEĆ POSTOJI VRATI PORUKU */
				return redirect()->back()->withFlashMessage($message);
			} else {
				
				$zahtjev = array('start_date' =>$input['start_date'], 'end_date' =>$input['end_date']);  // array zatjev početak - kraj
				$dani_zahtjev = $this->daniGO($zahtjev); 										//vraća broj dana zahtjeva
				
				//slobodni dani
				$slobodni_dani = $this->prekovremeni_bez_izlazaka($user); /* računa broj slobodnih dana prema prekovremenim satima */
				$koristeni_slobodni_dani = $this->koristeni_slobodni_dani($user);			//* računa iskorištene slobodne dane */
				$ukupnoDani = $this->ukupnoDani($zahtjev); //vraća dane zahtjeva
				$razlika_SLD = $slobodni_dani - $koristeni_slobodni_dani - $ukupnoDani;

				/*** Nova kalkulacija preostalih DANA */
				$godineZahtjeva = $this->godineZahtjeva();
				$dani_GO = 0;
				$datum = new DateTime('now');    /* današnji dan */
				$ova_godina = date_format($datum,'Y');
		
				foreach ($godineZahtjeva as $godina) {
					if( $godina == $ova_godina) {
						$dani_GO += GodisnjiController::razmjeranGO($user); 			// razmjerni dani ova godina
					} else {
						$dani_GO += GodisnjiController::razmjeranGO_PG($user, $godina); // razmjerni dani za sve godine
					}
				}
			
				$sviZahtjeviZaGodine = $this->zahtjeviSveGodine($user);
				$brojDanaZahtjeva = 0;
				foreach($sviZahtjeviZaGodine  as $zahtjevi) {
					$brojDanaZahtjeva += count($zahtjevi);
				}

				$razlika_dana = $dani_GO - $brojDanaZahtjeva;      // razlika razmjerni dani - dani zahtjevi
			
				if(!Sentinel::inRole('administrator') && $input['zahtjev'] == 'GO' && $razlika_dana < 0  ) {
					$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_dana . ' dana na datum završetka zahtjeva');
					return redirect()->back()->withFlashMessage($message);
				
				} else {
					$data = array(
						'zahtjev'  			=> $input['zahtjev'],
						'employee_id'  		=> $user->employee_id,
						'start_date'    	=> date("Y-m-d", strtotime($input['start_date'])),
						'end_date'			=> date("Y-m-d", strtotime($input['end_date'])),
						'start_time'  		=> $input['start_time'],
						'end_time'  		=> $input['end_time'],
						'napomena'  		=> $input['napomena']
					);
					if($input['zahtjev'] == 'Bolovanje'){
						$data += ['odobreno' => 'DA'];
					}

					$vacationRequest = new VacationRequest();
					$vacationRequest->saveVacationRequest($data);

					if($input['zahtjev'] == 'GO'){
						$zahtjev2 = 'korištenje godišnjeg odmora';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'COVID-19'){
						$zahtjev2 = 'oslobođenje od rada-COVID-19';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'RD'){
						$zahtjev2 = 'rad od doma';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'Izlazak') {
						$zahtjev2 = 'prijevremeni izlaz';
						$vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
					} elseif($input['zahtjev'] == 'Bolovanje'){
						$zahtjev2 = 'bolovanje';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'SLD'){
						$zahtjev2 = 'slobodan dan';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'NPL'){
						$zahtjev2 = 'neplaćeni dopust';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'PL'){
						$zahtjev2 = 'plaćeni dopust';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'VIK'){
						$zahtjev2 = 'slobodan vikend';
						$vrijeme="";
					} elseif($input['zahtjev'] == 'CEK'){
						$zahtjev2 = 'čekanje';
						$vrijeme="";
					}
					
					$employee = Employee::where('employees.id',$user->employee_id)->first();
					$employee_departments = array();

					foreach ( $employee->departments as $employee_department) {
						array_push($employee_departments,$employee_department->department['name']);
					}

					if( $input['email'] == 'DA' ) {
						$work = Work::where('id', $user->radnoMjesto_id)->first();   //radno mjesto zaposlenika
						// $department = $work->department; 							// odjel kojem pripada radno mjesto
						// $department_nadredjeni = $department->employee; 			 // nadređeni odjela
						$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta
						$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
						$registration_superior = $user->superior;   				// nadređeni djelatnik - Registration
						/*
						if($registration_superior) {
							$mail_work_voditelj = $registration_superior->email;
						} */
						
						if($work_voditelj) {
							$mail_work_voditelj = $work_voditelj->email;
						} elseif($work_nadredjeni) {
							$mail_work_voditelj = $work_nadredjeni->email;	
						}

						$ja = 'jelena.juras@duplico.hr';
						try {
							if(in_array('Radiona',$employee_departments) && $input['zahtjev'] == 'Izlazak') {
								$send_to = 'borislav.peklic@duplico.hr';
							//	$send_to = 'jelena.juras@duplico.hr';
	
								Mail::queue(
									'email.zahtjevGO',
									['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
									function ($message) use ($send_to, $employee) {
										$message->to($send_to)
											->from('info@duplico.hr', 'Duplico')
											->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
									}
								);
							} else {
								if(isset($mail_work_voditelj)){
									if($input['zahtjev'] == 'Bolovanje'){ 			// ako je bolovanje
										Mail::queue(
											'email.zahtjevGO',
											['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
											function ($message) use ($ja, $employee) {
												$message->to('matija.barberic@duplico.hr')
														->from('info@duplico.hr', 'Duplico')
														->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
											}
										);
										Mail::queue(
											'email.zahtjevGO',
											['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
											function ($message) use ($mail_work_voditelj, $employee) {
												$message->to($mail_work_voditelj)
														->from('info@duplico.hr', 'Duplico')
														->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
											}
										);
									} else {										// svi ostali zahtjevi
										if($mail_work_voditelj != '' || $mail_work_voditelj != null) {
											Mail::queue(
												'email.zahtjevGO',
												['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
												function ($message) use ($mail_work_voditelj, $employee) {
													$message->to($mail_work_voditelj)
														->from('info@duplico.hr', 'Duplico')
														->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
												}
											);
											Mail::queue(
												'email.zahtjevGO',
												['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
												function ($message) use ($ja, $employee) {
													$message->to($ja)
														->from('info@duplico.hr', 'Duplico')
														->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
												}
											);
										}
									}
								}	
							}
												
						} catch (\Throwable $th) {
							$message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
							return redirect()->back()->withFlashMessage($message);
						}
					}
				}
			}			
		}

		$message = session()->flash('success', 'Zahtjev je poslan');

		return redirect()->route('admin.vacation_requests.index')->withFlashMessage($message);
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$employee = Employee::find($id);
		$afterHours = AfterHour::where('employee_id', $employee->id)->get();
		$vacationRequests = VacationRequest::where('employee_id', $employee->id)->get();
		
		$registration = Registration::where('employee_id',  $employee->id)->first();
		
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;
			
		return view('admin.vacation_requests.show', ['vacationRequests' => $vacationRequests,'afterHours' => $afterHours,'registration' => $registration,'ova_godina' => $ova_godina,'prosla_godina' => $prosla_godina ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vacationRequest = VacationRequest::find($id);
		
		$employee = Employee::where('employees.id',$vacationRequest->employee_id)->first();
		$registration = Registration::where('registrations.employee_id', $employee->id)->first();
		
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		/*	
		$dani_GO = $this->godisnji($employee);
		$razmjeranGO = $this->razmjeranGO($registration);
		$razmjeranGO_PG = $this->razmjeranGO_PG($registration);
		
		$daniZahtjevi = $this->daniZahtjevi($registration);
		$daniZahtjevi_PG = $this->daniZahtjeviPG($registration);
		*/
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($registration);
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($registration);
		
		$godineZahtjeva = $this->godineZahtjeva();
		$GO_dani = 0;
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');

		foreach ($godineZahtjeva as $godina) {
			if( $godina == $ova_godina) {
				$GO_dani += GodisnjiController::razmjeranGO($registration); // razmjerni dani ova godina
			
			} else {
				$GO_dani += GodisnjiController::razmjeranGO_PG($registration, $godina); // razmjerni dani za sve godine
			}
			
		}
	
		$sviZahtjeviZaGodine = $this->zahtjeviSveGodine($registration);
		$brojDanaZahtjeva = 0;
		foreach($sviZahtjeviZaGodine  as $zahtjevi) {
			$brojDanaZahtjeva += count($zahtjevi); 
		}
	
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($registration);
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($registration);

		$preostali_dani = $GO_dani - $brojDanaZahtjeva;

		//		return view('admin.vacation_requests.edit', ['vacationRequest' => $vacationRequest])->with('registration', $registration)->with('registrations', $registrations)->with('employee', $employee)->with('daniZahtjevi', $daniZahtjevi)->with('daniZahtjevi_PG', $daniZahtjevi_PG)->with('slobodni_dani', $slobodni_dani )->with('koristeni_slobodni_dani', $koristeni_slobodni_dani)->with('razmjeranGO', $razmjeranGO)->with('razmjeranGO_PG', $razmjeranGO_PG )->with('preostali_dani', $preostali_dani );

		return view('admin.vacation_requests.edit', ['vacationRequest' => $vacationRequest,'registration' => $registration,'registrations' => $registrations,'employee' => $employee,'slobodni_dani' => $slobodni_dani,'koristeni_slobodni_dani' => $koristeni_slobodni_dani, 'preostali_dani' => $preostali_dani]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Vacation_RequestRequest $request, $id)
    {
		$vacationRequest = VacationRequest::find($id);

		$input = $request->except(['_token']);
	
		if($input['end_date'] == '' ){
			$input['end_date'] = $input['start_date'];
		}
	
		$user = Registration::where('employee_id', $input['employee_id'] )->first();
		$superior_mail = $user->superior['email'];   //mail nadređenog djelatnika

		$razmjeranGO = $this->razmjeranGO($user);
		$daniZahtjevi = $this->daniZahtjevi($user);
		$neiskoristen_GO = $razmjeranGO - $daniZahtjevi;
		
		// $razmjeranGO_PG = $this->razmjeranGO_PG($user);
		// $daniZahtjevi_PG = $this->daniZahtjeviPG($user);
		// $neiskoristen_GO_PG = $razmjeranGO_PG - $daniZahtjevi_PG;
		
		//	$dani_GO = $neiskoristen_GO + $neiskoristen_GO_PG; //vraća razmjeran GO - iskorišteni dani -  nalazi se i u input ['Dani'])
			
		$zahtjev = array('start_date' =>$input['start_date'], 'end_date' =>$input['end_date']);
		$dani_zahtjev = $this->daniGO($zahtjev); //vraća dane zahtjeva
		//$razlika_dana =  $dani_GO - $dani_zahtjev;
		
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($user); /* računa broj slobodnih dana prema prekovremenim satima */
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($user);/* računa iskorištene slobodne dane */
		
		$ukupnoDani = $this->ukupnoDani($zahtjev); //vraća dane zahtjeva
		
		/*** Nova kalkulacija DANA */
		$godineZahtjeva = $this->godineZahtjeva();
		$dani_GO = 0;
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');

		foreach ($godineZahtjeva as $godina) {
			if( $godina == $ova_godina) {
				$dani_GO += GodisnjiController::razmjeranGO($user); // razmjerni dani ova godina			
			} else {
				$dani_GO += GodisnjiController::razmjeranGO_PG($user, $godina); // razmjerni dani za sve godine
			}
		}
	
		$sviZahtjeviZaGodine = $this->zahtjeviSveGodine($user);
		$brojDanaZahtjeva = 0;
		
		foreach($sviZahtjeviZaGodine  as $zahtjevi) {
			$brojDanaZahtjeva += count($zahtjevi); 
		}

		$razlika_dana = $dani_GO - $brojDanaZahtjeva;

		if(!Sentinel::inRole('administrator') && $input['zahtjev'] == 'GO' && $razlika_dana < 0 ){
			$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_dana . ' dana');
			return redirect()->back()->withFlashMessage($message);
		
		} else {
			$data = array(
				'zahtjev'  			=> $input['zahtjev'],
				'employee_id'  		=> $input['employee_id'],
				'start_date'    		=> date("Y-m-d", strtotime($input['start_date'])),
				'end_date'		=> date("Y-m-d", strtotime($input['end_date'])),
				'start_time'  		=> $input['start_time'],
				'end_time'  		=> $input['end_time'],
				'napomena'  		=> $input['napomena']
			);
			if($input['zahtjev'] == 'Bolovanje'){
				$data += ['odobreno' => 'DA'];
			}

			$vacationRequest->updateVacationRequest($data);

			$employee = Employee::where('employees.id',$user->employee_id)->first();

			if($input['zahtjev'] == 'GO'){
				$zahtjev2 = 'korištenje godišnjeg odmora';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'COVID-19'){
				$zahtjev2 = 'oslobođenje od rada-COVID-19';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'RD'){
				$zahtjev2 = 'rad od doma';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'Izlazak') {
				$zahtjev2 = 'prijevremeni izlaz';
				$vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
			} elseif($input['zahtjev'] == 'Bolovanje'){
				$zahtjev2 = 'bolovanje';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'SLD'){
				$zahtjev2 = 'slobodan dan';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'NPL'){
				$zahtjev2 = 'neplaćeni dopust';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'PL'){
				$zahtjev2 = 'plaćeni dopust';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'VIK'){
				$zahtjev2 = 'slobodan vikend';
				$vrijeme="";
			} elseif($input['zahtjev'] == 'CEK'){
				$zahtjev2 = 'čekanje';
				$vrijeme="";
			}
			
			if($input['email'] == 'DA' ){
				$work = Work::where('id', $user->radnoMjesto_id)->first();   //radno mjesto zaposlenika
				/* $department = $work->department; 							// odjel kojem pripada radno mjesto
				$department_nadredjeni = $department->employee; 			 // nadređeni odjela */
				$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta
				$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
				$registration_superior = $user->superior;   				// nadređeni djelatnik - Registration

			/*	if($registration_superior) {
					$mail_work_voditelj = $registration_superior->email;
				} 
				*/
				if($work_voditelj) {
					$mail_work_voditelj = $work_voditelj->email;	
				} elseif($work_nadredjeni) {
					$mail_work_voditelj = $work_nadredjeni->email;	
				}

				if(isset($mail_work_voditelj)){
					if($mail_work_voditelj != '' || $mail_work_voditelj != null) {
						Mail::queue(
							'email.zahtjevGO',
							['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
							function ($message) use ($mail_work_voditelj, $employee) {
								$message->to($mail_work_voditelj)
									->from('info@duplico.hr', 'Duplico')
									->subject('Ispravak zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
							}
						);
						Mail::queue(
							'email.zahtjevGO',
							['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $razlika_dana ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
							function ($message) use ($employee) {
								$message->to('jelena.juras@duplico.hr')
									->from('info@duplico.hr', 'Duplico')
									->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
							}
						);
					}
				}
			}
		}
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
			
		//return redirect()->back()->withFlashMessage($messange);
		//return redirect()->route('home')->withFlashMessage($message);
		return redirect()->route('admin.vacation_requests.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vacationRequest = VacationRequest::find($id);

		$vacationRequest->delete();
		
		$message = session()->flash('success', 'Zahtjev je obrisan.');
		
		return redirect()->route('admin.vacation_requests.index')->withFlashMessage($message);
    }
	
	public function getActivate(Request $request)
    {
		return redirect()->route('admin.vacation_requests.storeConf');
    }
		
	// odobrenje direktno sa portala 
	public function confDirector(Request $request) 
	{
		$datum = new DateTime('now');
		$vacationRequest = VacationRequest::find($request['id']);
		$nadredjeni_uprava_id = $vacationRequest->employee->work->nadredjeni->id;
		$nadredjeni_voditelj_id = $vacationRequest->employee->work->prvi_nadredjeni ? $vacationRequest->employee->work->prvi_nadredjeni->id : null;

		$user = Sentinel::getUser(); 	// prijavljena osoba - odobrava
		$odobrio_user = Employee::where('employees.first_name', $user->first_name)->where('employees.last_name', $user->last_name)->first(); // prijavljeni djelatnik - odobrava
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name ;
		if(Sentinel::inRole('odobrenja_uprave')) {
			$odobrio = 'po nalogu člana uprave ' . $odobrio;
		}

		if( $request['odobreno']) {
			$odobreno =  $request['odobreno'];
		}
		if( $request['odobreno2']) {
			$odobreno =  $request['odobreno2'];
		}

		$data = array(
			'odobrio_id'    =>  $odobrio_user->id,			
			'odobreno'    	=>  $odobreno,			
			'razlog'  		=> "direktno odobrenje sa portala!",
			'datum_odobrenja'=>  date_format($datum,'Y-m-d')
		);

		$vacationRequest->updateVacationRequest($data);
		//send mail
		$subject = 'Odobrenje zahtjeva ';
		$odobrenje = 'je odobren';

		if( ( $vacationRequest->odobreno == 'NE' ) ) {
			$odobrenje = 'nije odobren';
			$subject = 'Odbijen zahtjev ';
		}

		if( $vacationRequest->zahtjev == 'GO'){
			$zahtjev2 = 'korištenje godišnjeg odmora';			
		} elseif($vacationRequest->zahtjev == 'COVID-19'){
			$zahtjev2 = 'oslobođenje od rada - COVID-19';
			$vrijeme="";
		} elseif($vacationRequest->zahtjev == 'RD'){
			$zahtjev2 = 'rad od doma';
			$vrijeme="";
		}  elseif ($vacationRequest->zahtjev == 'Bolovanje') {
			$zahtjev2 = 'bolovanje';
		} elseif ($vacationRequest->zahtjev == 'Izlazak'){
			$zahtjev2 = 'izlazak';
		} elseif ($vacationRequest->zahtjev == 'SLD'){
			$zahtjev2 = 'slobodan dan';
		} elseif ($vacationRequest->zahtjev == 'PL'){
			$zahtjev2 = 'plaćeni dopust';
		} elseif ($vacationRequest->zahtjev == 'NPL'){
			$zahtjev2 = 'neplaćeni dopust';
		} elseif ($vacationRequest->zahtjev == 'VIK'){
			$zahtjev2 = 'slobodan vikend';
		} elseif ($vacationRequest->zahtjev == 'CEK'){
			$zahtjev2 = 'čekanje';
		} else {
			$zahtjev2 = '';
		}

		if($vacationRequest->napomena) {
			$zahtjev2 .= ' (' . $vacationRequest->napomena . ')';
		}
		
		$employee = $vacationRequest->employee;
		$ime = $employee->first_name . ' ' . $employee->last_name;
		$work = $vacationRequest->employee->work;   					//radno mjesto zaposlenika
		/* $department = $vacationRequest->employee->work->department; 	// odjel kojem pripada radno mjesto
		$department_nadredjeni = $department->employee; 			 	// nadređeni odjela */
		$work_nadredjeni = $work->nadredjeni;    						// glavni nadređeni radnog mjesta - član uprave
		$work_voditelj = $work->prvi_nadredjeni;    					// voditelj radnog mjesta
		$email_work_nadredjeni = $work_nadredjeni->email;				// email glavnog nadređenog
		$superior_mail = $vacationRequest->employee->superior['email'];   	//mail nadređenog djelatnika
		$employee_mail = $vacationRequest->employee['email'];
		
		$uprava = array('zeljko.rendulic@duplico.hr','durdica.rendulic@duplico.hr','ivan.rendulic@duplico.hr','nikola.rendulic@duplico.hr','matija.rendulic@duplico.hr');
		$mail_to = array($employee_mail, $email_work_nadredjeni, $superior_mail,'pravni@duplico.hr','jelena.juras@duplico.hr');

		$mails = array_diff( array_unique(array_merge($uprava, $mail_to)), array( $odobrio_user->email )); // svi mailovi uprava, djelatnik i voditelj - bez duplih, bez onog tko je odobrio
		//$mails = array('jelena.juras@duplico.hr');

    	try {
			foreach($mails as $mail) {
				if($mail != '' && $mail != null) {
					Mail::queue(
						'email.zahtjevOD_uprave',
						['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $request['razlog'], 'odobrio' => $odobrio, 'ime' => $ime, 'subject' => $subject],
						function ($message) use ($mail, $employee, $subject) {
							$message->to($mail)
								->from('info@duplico.hr', 'Duplico')
								->subject($subject . ' - ' .  $employee->first_name . ' ' . $employee->last_name );
						}
					);
				}
			}
		} catch (\Throwable $th) {
			$message = session()->flash('error', 'Nešto je pošlo krivo, javite se administratoru portala');
			
			return redirect()->route('home')->withFlashMessage($message);
		}


		$message = session()->flash('success', 'Zahtjev je odobren i poslan je mail');
		return redirect()->back();
	}

	public function storeConf(Request $request)
    {
		$input = $request->except(['_token']);
		$vacationRequest = VacationRequest::find($input['id']);
		
		if(! $vacationRequest) {
			$message = session()->flash('error', 'Nešto je pošlo krivo, zahtjev nije nađen u bazi, javite se administratoru portala');
		
			return redirect()->route('home')->withFlashMessage($message);
		}
		$user = Sentinel::getUser(); 																	// prijavljena osoba - odobrava
		$odobrio_user = Employee::where('employees.first_name', $user->first_name)->where('employees.last_name', $user->last_name)->first(); // prijavljeni djelatnik - odobrava
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name ;					// ime prijavljenog djelatnika koji odobrava
		/*
			if($vacationRequest->odobreno == "DA") {
				return view('admin.confirmation_show', ['employee' => $odobrio_user->id, 'vacationRequest_id' => $vacationRequest->id,  'vacationRequest' => $vacationRequest ]);
			}
		*/
	
		$employee = Registration::where('employee_id', $vacationRequest->employee_id)->first();    // djelatnik koji je poslao zahtjev 			
		$ime = $employee->employee['first_name'] . ' ' . $employee->employee['last_name'];  // ime djelatnika koji je poslao zahtjev 
		$employee_mail = $employee->employee['email'];										// mail djelatnika koji je poslao zahtjev 
					
		$datum = new DateTime('now');
		
		if( $request['odobreno']) {
			$odobreno =  $request['odobreno'];
		}
		if( $request['odobreno2']) {
			$odobreno =  $request['odobreno2'];
		}
		$data = array(
			'odobrio_id'    	=>  $odobrio_user->id,
			'odobreno'    		=>  $odobreno ,
			'razlog'  			=>  $request['razlog'],
			'datum_odobrenja'	=>  date_format($datum,'Y-m-d')
		);

		$vacationRequest->updateVacationRequest($data);
		
		if($input['email'] == 'DA' ){ 
			$subject = 'Odobrenje zahtjeva ';

			if( ( $vacationRequest->odobreno == 'DA' ) ) {
				$odobrenje = 'je odobren';
			} 
			if( ( $vacationRequest->odobreno == 'NE' ) ) {
				$odobrenje = 'nije odobren';
				$subject = 'Odbijen zahtjev ';
			}
			
			if( $vacationRequest->zahtjev == 'GO'){
				$zahtjev2 = 'korištenje godišnjeg odmora';			
			} elseif($vacationRequest->zahtjev == 'COVID-19'){
				$zahtjev2 = 'oslobođenje od rada-COVID-19';
			} elseif($vacationRequest->zahtjev == 'RD'){
				$zahtjev2 = 'rad od doma';
				$vrijeme="";
			} elseif ($vacationRequest->zahtjev == 'Bolovanje') {
				$zahtjev2 = 'bolovanje';
			} elseif ($vacationRequest->zahtjev == 'Izlazak'){
				$zahtjev2 = 'izlazak';
			} elseif ($vacationRequest->zahtjev == 'SLD'){
				$zahtjev2 = 'slobodan dan';
			} elseif ($vacationRequest->zahtjev == 'PL'){
				$zahtjev2 = 'plaćeni dopust';
			} elseif ($vacationRequest->zahtjev == 'NPL'){
				$zahtjev2 = 'neplaćeni dopust';
			} elseif ($vacationRequest->zahtjev == 'VIK'){
				$zahtjev2 = 'slobodan vikend';
			} elseif ($vacationRequest->zahtjev == 'CEK'){
				$zahtjev2 = 'čekanje';
			} else {
				$zahtjev2 = '';
			}

			if($vacationRequest->napomena) {
				$zahtjev2 .= ' (' . $vacationRequest->napomena . ')';
			}
			
			$work = Work::where('id', $employee->radnoMjesto_id)->first();   //radno mjesto zaposlenika
			/* $department = $work->department; 							// odjel kojem pripada radno mjesto
			$department_nadredjeni =  $work->nadredjeni;   			 // nadređeni odjela */
			$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta - član uprave
			$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
			$email_work_nadredjeni = $work_nadredjeni->email;				// email glavnog nadređenog
			$superior_mail = $employee->superior['email'];   //mail nadređenog djelatnika

			$uprava = array('zeljko.rendulic@duplico.hr','durdica.rendulic@duplico.hr','ivan.rendulic@duplico.hr','nikola.rendulic@duplico.hr','matija.rendulic@duplico.hr');
			$mail_to = array($email_work_nadredjeni, $superior_mail,'pravni@duplico.hr','jelena.juras@duplico.hr');

			$mails = array_diff( array_unique(array_merge($uprava, $mail_to)), array( $odobrio_user->email )); // svi mailovi uprava, djelatnik i voditelj - bez duplih, bez onog tko je odobrio
			$mail_djelatnik = $employee_mail;

			try {
				foreach($mails as $mail) {  
					if($mail != '' && $mail != null) {
						Mail::queue(
							'email.zahtjevOD_uprave',    // mail sa svim podacima
							['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $request['razlog'], 'odobrio' => $odobrio, 'ime' => $ime, 'subject' => $subject],
							function ($message) use ($mail, $employee, $subject) {
								$message->to($mail)
									->from('info@duplico.hr', 'Duplico')
									->subject($subject . ' - ' .  $employee->employee['first_name'] . ' ' . $employee->employee['last_name']);
							}
						);
					}
				}
				Mail::queue(   
					'email.zahtjevOD_djelatniku',     // mail za djelatnika podacima
					['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $request['razlog'], 'odobrio' => $odobrio, 'ime' => $ime, 'subject' => $subject],
					function ($message) use ($mail_djelatnik, $employee, $subject) {
						$message->to($mail_djelatnik)
							->from('info@duplico.hr', 'Duplico')
							->subject($subject . ' - ' .  $employee->employee['first_name'] . ' ' . $employee->employee['last_name']);
					}
				);
			} catch (\Throwable $th) {
				$message = session()->flash('error', 'Nešto je pošlo krivo, javite se administratoru portala');
				
				return redirect()->route('home')->withFlashMessage($message);
			}	
			
			}
			if( ( $vacationRequest->odobreno == 'DA' ) ) {
				$message = session()->flash('success', 'Poruka je poslana, zahtjev je odobren');
			} 
			if( ( $vacationRequest->odobreno == 'NE' ) ) {
				$message = session()->flash('success', 'Poruka je poslana, zahtjev je odbijen');
			}
			
			return redirect()->route('home')->withFlashMessage($message);			
    }
	
	public function confirmation_show(Request $request)
	{
		$user = Sentinel::getUser();
		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();

		if(isset($request['vacationRequest_id'])) {
			$vacationRequest = VacationRequest::find( $request['vacationRequest_id']);
		} else {
			$vacationRequest = VacationRequest::find($request['id']);
		}
		
		$vacationRequest_id = 	$vacationRequest->id;

		return view('admin.confirmation_show',['vacationRequest' => $vacationRequest, 'vacationRequest_id'=> $vacationRequest_id,'employee'=> $employee ]);
	}
	
	public function VacationRequest(Request $request)
	{
		$vacationRequest = VacationRequest::where('id',$request->id)->first();
		
		$zahtjev = '';
		if($vacationRequest->zahtjev == 'GO'){
			$zahtjev = 'korištenje godišnjeg odmora';			
		} elseif($vacationRequest->zahtjev == 'COVID-19'){
			$zahtjev2 = 'oslobođenje od rada-COVID-19';
		} elseif($vacationRequest->zahtjev == 'RD'){
			$zahtjev2 = 'rad od doma';
			$vrijeme="";
		} elseif($vacationRequest->zahtjev == 'Bolovanje') {
			$zahtjev = 'bolovanje';
		} elseif($vacationRequest->zahtjev == 'Izlazak'){
			$zahtjev = 'izlazak';
		} elseif($vacationRequest->zahtjev == 'SLD'){
			$zahtjev = 'slobodan dan';
		} elseif($vacationRequest->zahtjev == 'PL'){
			$zahtjev = 'plaćeni dopust';
		} elseif($vacationRequest->zahtjev == 'NPL'){
			$zahtjev = 'neplaćeni dopust';
		} elseif($vacationRequest->zahtjev == 'VIK'){
			$zahtjev = 'slobodan vikend';
		} elseif ($vacationRequest->zahtjev == 'CEK'){
			$zahtjev2 = 'čekanje';
		} else {
			$zahtjev2 = '';
		}
		
		$dani = array('start_date' =>$vacationRequest->start_date, 'end_date' =>$vacationRequest->end_date);
		$daniGO = $this->daniGO($dani); //vraća dane zahtjeva

		return view('admin.VacationRequest', ['vacationRequest' => $vacationRequest, 'zahtjev' => $zahtjev, 'daniGO' => $daniGO]);
	}
	
	public function AllVacationRequest(Request $request)
	{
		
		$datum = explode('-',$request['mjesec']);
		$godina = $datum[0];
        $mjesec = $datum[1];

		$vacationRequests = VacationRequest::where('odobreno','DA')->whereMonth('start_date',$mjesec)->whereYear('start_date', $godina)->get();
		$vacationRequests = $vacationRequests->merge(VacationRequest::where('odobreno','DA')->whereMonth('end_date',$mjesec)->whereYear('start_date',$godina)->get());

		return view('admin.AllVacationRequest', ['vacationRequests' => $vacationRequests,'mjesec' => $mjesec,'godina' => $godina]);
	}
}
