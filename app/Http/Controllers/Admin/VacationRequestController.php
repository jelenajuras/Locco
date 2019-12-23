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
		
		if(Sentinel::inRole('administrator')){
			$requests = VacationRequest::orderBy('created_at','DESC')->get();
			$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		} else {
			$requests = VacationRequest::where('employee_id',$employee->id)->orderBy('created_at','DESC')->get();
			$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->where('employee_id',$employee->id)->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		}

		return view('admin.vacation_requests.index',['registrations'=>$registrations])->with('requests', $requests)->with('prekovremeni', $prekovremeni);
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
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($registration);
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($registration);
		$preostali_dani = $this->zahtjevi_novo($registration)['preostalo_PG'] + $this->zahtjevi_novo($registration)['preostalo_OG'];

		//return view('admin.vacation_requests.create')->with('registration', $registration)->with('registrations', $registrations)->with('employee', $employee)->with('daniZahtjevi', $daniZahtjevi)->with('daniZahtjevi_PG', $daniZahtjevi_PG)->with('slobodni_dani', $slobodni_dani )->with('koristeni_slobodni_dani', $koristeni_slobodni_dani)->with('razmjeranGO', $razmjeranGO)->with('razmjeranGO_PG', $razmjeranGO_PG );
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

		if($input['GOzavršetak'] == '' ){
			$input['GOzavršetak'] = $input['GOpocetak'];
		}
		if($input['employee_id'] == 'svi'){   	
			$registrations = Registration::where('odjava', null)->get();    /* svi prijavljeni djelatnici */										/* AKO JE ZAHTJEV NA SVE DJELATNIKE */
			foreach($registrations as $registration){
					$data = array(
						'zahtjev'  			=> $input['zahtjev'],
						'employee_id'  		=> $registration->employee_id,
						'GOpocetak'    		=> date("Y-m-d", strtotime($input['GOpocetak'])),
						'GOzavršetak'		=> date("Y-m-d", strtotime($input['GOzavršetak'])),
						'vrijeme_od'  		=> $input['vrijeme_od'],
						'vrijeme_do'  		=> $input['vrijeme_do'],
						'odobreno' 			=> 'DA',
						'odobrio_id' 		=> '58',
						'napomena'  		=> $input['napomena']
					);
					$vacationRequest = new VacationRequest();
					$vacationRequest->saveVacationRequest($data);
			}
		} elseif(is_array($input['employee_id']) && count($input['employee_id'])>1) {   /* ZAHTJEV NA VIŠE DJELATNIKA */
			foreach($input['employee_id'] as $employee_id){
				$data = array(
				'zahtjev'  			=> $input['zahtjev'],
				'employee_id'  		=> $employee_id,
				'GOpocetak'    		=> date("Y-m-d", strtotime($input['GOpocetak'])),
				'GOzavršetak'		=> date("Y-m-d", strtotime($input['GOzavršetak'])),
				'vrijeme_od'  		=> $input['vrijeme_od'],
				'vrijeme_do'  		=> $input['vrijeme_do'],
				'napomena'  		=> $input['napomena'],
				'odobreno' 			=> '',   // ODOBRENO ILI NE????
				'odobrio_id' 		=> '58'
				);
				
				$vacationRequest = new VacationRequest();
				$vacationRequest->saveVacationRequest($data);
			}
		} else {  																			/* ZAHTJEV NA JEDNOG DJELATNIKA*/
			$user = Registration::where('employee_id', $input['employee_id'] )->first(); /* djelatnik */
			$employee_mail = $user->employee['email'];

			//GO preostali dani
			$GOzavršetak = new DateTime($request->GOzavršetak);

			$preostalo_dana_PG = $this->zahtjevi_novo($user)['preostalo_PG'];   //preostalo dana GO prošla godina
			$preostalo_dana_OG = $this->zahtjevi_novo($user)['preostalo_OG'];   //preostalo dana GO ova godina

		//	$preostalo_dana_OG = $this->razmjeranGO_date($user, $request) - $this->zahtjevi_novo($user)['zahtjevi_Dani_OG']; // dani GO ova godina na datum završetka zahtjeva - dani zahtjevi ova godina
			$dani_GO = $preostalo_dana_PG + $preostalo_dana_OG;
			
			$zahtjev = array('GOpocetak' =>$input['GOpocetak'], 'GOzavršetak' =>$input['GOzavršetak']);
			$dani_zahtjev = $this->daniGO($zahtjev); //vraća dane zahtjeva
			$razlika_dana =  $dani_GO - $dani_zahtjev; // vraća razliku preostalih dana i dana novog zahtjeva
			
			//slobodni dani
			$slobodni_dani = $this->prekovremeni_bez_izlazaka($user); /* računa broj slobodnih dana prema prekovremenim satima */
			$koristeni_slobodni_dani = $this->koristeni_slobodni_dani($user);/* računa iskorištene slobodne dane */
			$ukupnoDani = $this->ukupnoDani($zahtjev); //vraća dane zahtjeva
			$razlika_SLD = $slobodni_dani - $ukupnoDani;

			if(!Sentinel::inRole('administrator') && $input['zahtjev'] == 'GO' && $razlika_dana < 0  ){   //&& $user->work['job_description'] != 'montaža'
				$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_dana . ' dana na datum završetka zahtjeva');
				return redirect()->back()->withFlashMessage($message);
			//} elseif(!Sentinel::inRole('administrator') && $input['zahtjev'] == 'SLD' && $razlika_SLD < 0 ){
				//$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . - $razlika_SLD . ' dana');
				//return redirect()->back()->withFlashMessage($message);
			} else {
				$data = array(
					'zahtjev'  			=> $input['zahtjev'],
					'employee_id'  		=> $user->employee_id,
					'GOpocetak'    		=> date("Y-m-d", strtotime($input['GOpocetak'])),
					'GOzavršetak'		=> date("Y-m-d", strtotime($input['GOzavršetak'])),
					'vrijeme_od'  		=> $input['vrijeme_od'],
					'vrijeme_do'  		=> $input['vrijeme_do'],
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
				} elseif($input['zahtjev'] == 'Izlazak') {
					$zahtjev2 = 'prijevremeni izlaz';
					$vrijeme = 'od ' . $input['vrijeme_od'] . ' do ' . $input['vrijeme_do']; 
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
				}
				
				$employee = Employee::where('employees.id',$user->employee_id)->first();
				
				if($input['email'] == 'DA' ){
					$work = Work::where('id', $user->radnoMjesto_id)->first();   //radno mjesto zaposlenika
					//	$department = $work->department; 							// odjel kojem pripada radno mjesto
					// $department_nadredjeni = $department->employee; 			 // nadređeni odjela
					$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta
					$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
					$registration_superior = $user->superior;   				// nadređeni djelatnik - Registration

					if($registration_superior) {
						$mail_work_voditelj = $registration_superior->email;
					} elseif($work_voditelj) {
						$mail_work_voditelj = $work_voditelj->email;	
					} elseif($work_nadredjeni) {
						$mail_work_voditelj = $work_nadredjeni->email;	
					}

					$ja = 'jelena.juras@duplico.hr';
					try {
						if(isset($mail_work_voditelj)){
							if($mail_work_voditelj != '' || $mail_work_voditelj != null) {
								Mail::queue(
									'email.zahtjevGO',
									['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
									function ($message) use ($mail_work_voditelj, $employee) {
										$message->to($mail_work_voditelj)
											->from('info@duplico.hr', 'Duplico')
											->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
									}
								);
								Mail::queue(
									'email.zahtjevGO',
									['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
									function ($message) use ($ja, $employee) {
										$message->to($ja)
											->from('info@duplico.hr', 'Duplico')
											->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
									}
								);
							}
						}
						if($input['zahtjev'] == 'Bolovanje'){
							Mail::queue(
								'email.zahtjevGO',
								['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
								function ($message) use ($ja, $employee) {
									$message->to('matija.barberic@duplico.hr')
											->from('info@duplico.hr', 'Duplico')
											->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
								}
							);
							Mail::queue(
								'email.zahtjevGO',
								['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
								function ($message) use ($mail_work_voditelj, $employee) {
									$message->to($mail_work_voditelj)
											->from('info@duplico.hr', 'Duplico')
											->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
								}
							);
						}
					} catch (\Throwable $th) {
						$message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
						return redirect()->back()->withFlashMessage($message);
					}
					
					
				}
			}
		}

		$message = session()->flash('success', 'Zahtjev je poslan');
			
		// return redirect()->back()->withFlashMessage($message);
		return redirect()->route('index')->withFlashMessage($message);
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
		
		$dani_GO = $this->godisnji($employee);
		$razmjeranGO = $this->razmjeranGO($registration);
		$razmjeranGO_PG = $this->razmjeranGO_PG($registration);
		
		$daniZahtjevi = $this->daniZahtjevi($registration);
		$daniZahtjevi_PG = $this->daniZahtjeviPG($registration);
		
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($registration);
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($registration);

		return view('admin.vacation_requests.edit', ['vacationRequest' => $vacationRequest])->with('registration', $registration)->with('registrations', $registrations)->with('employee', $employee)->with('daniZahtjevi', $daniZahtjevi)->with('daniZahtjevi_PG', $daniZahtjevi_PG)->with('slobodni_dani', $slobodni_dani )->with('koristeni_slobodni_dani', $koristeni_slobodni_dani)->with('razmjeranGO', $razmjeranGO)->with('razmjeranGO_PG', $razmjeranGO_PG );
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
	
		if($input['GOzavršetak'] == '' ){
			$input['GOzavršetak'] = $input['GOpocetak'];
		}
	
		$user = Registration::where('employee_id', $input['employee_id'] )->first();
		$superior_mail = $user->superior['email'];   //mail nadređenog djelatnika

		$razmjeranGO = $this->razmjeranGO($user);
		$daniZahtjevi = $this->daniZahtjevi($user);
		$neiskoristen_GO = $razmjeranGO - $daniZahtjevi;
		
		$razmjeranGO_PG = $this->razmjeranGO_PG($user);
		$daniZahtjevi_PG = $this->daniZahtjeviPG($user);
		$neiskoristen_GO_PG = $razmjeranGO_PG - $daniZahtjevi_PG;
		
		$dani_GO = $neiskoristen_GO + $neiskoristen_GO_PG; //vraća razmjeran GO - iskorišteni dani -  nalazi se i u input ['Dani'])
			
		$zahtjev = array('GOpocetak' =>$input['GOpocetak'], 'GOzavršetak' =>$input['GOzavršetak']);
		$dani_zahtjev = $this->daniGO($zahtjev); //vraća dane zahtjeva
		$razlika_dana =  $dani_GO - $dani_zahtjev;
		
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($user); /* računa broj slobodnih dana prema prekovremenim satima */
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($user);/* računa iskorištene slobodne dane */
		
		$ukupnoDani = $this->ukupnoDani($zahtjev); //vraća dane zahtjeva
		$razlika_SLD = $slobodni_dani - $ukupnoDani;
		
		if(!Sentinel::inRole('administrator') && $input['zahtjev'] == 'GO' && $razlika_dana < 0 ){
			$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_dana . ' dana');
			return redirect()->back()->withFlashMessage($message);
		//} elseif($input['zahtjev'] == 'SLD' && $razlika_SLD < 0 ){
		//	$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_SLD . ' dana');
		//	return redirect()->back()->withFlashMessage($message);
		} else {
			$data = array(
				'zahtjev'  			=> $input['zahtjev'],
				'employee_id'  		=> $input['employee_id'],
				'GOpocetak'    		=> date("Y-m-d", strtotime($input['GOpocetak'])),
				'GOzavršetak'		=> date("Y-m-d", strtotime($input['GOzavršetak'])),
				'vrijeme_od'  		=> $input['vrijeme_od'],
				'vrijeme_do'  		=> $input['vrijeme_do'],
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
			}elseif($input['zahtjev'] == 'Izlazak') {
				$zahtjev2 = 'prijevremeni izlaz';
				$vrijeme = 'od ' . $input['vrijeme_od'] . ' do ' . $input['vrijeme_do']; 
			}elseif($input['zahtjev'] == 'Bolovanje'){
				$zahtjev2 = 'bolovanje';
				$vrijeme="";
			}elseif($input['zahtjev'] == 'SLD'){
				$zahtjev2 = 'slobodan dan';
				$vrijeme="";
			}elseif($input['zahtjev'] == 'NPL'){
				$zahtjev2 = 'neplaćeni dopust';
				$vrijeme="";
			}elseif($input['zahtjev'] == 'PL'){
				$zahtjev2 = 'plaćeni dopust';
				$vrijeme="";
			}elseif($input['zahtjev'] == 'VIK'){
				$zahtjev2 = 'slobodan vikend';
				$vrijeme="";
			}
			
			if($input['email'] == 'DA' ){
				$work = Work::where('id', $user->radnoMjesto_id)->first();   //radno mjesto zaposlenika
				$department = $work->department; 							// odjel kojem pripada radno mjesto
				$department_nadredjeni = $department->employee; 			 // nadređeni odjela
				$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta
				$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
				$registration_superior = $user->superior;   				// nadređeni djelatnik - Registration

				if($registration_superior) {
					$mail_work_voditelj = $registration_superior->email;
				} elseif($work_voditelj) {
					$mail_work_voditelj = $work_voditelj->email;	
				} elseif($work_nadredjeni) {
					$mail_work_voditelj = $work_nadredjeni->email;	
				}

				if(isset($mail_work_voditelj)){
					if($mail_work_voditelj != '' || $mail_work_voditelj != null) {
						Mail::queue(
							'email.zahtjevGO',
							['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
							function ($message) use ($mail_work_voditelj, $employee) {
								$message->to($mail_work_voditelj)
									->from('info@duplico.hr', 'Duplico')
									->subject('Ispravak zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
							}
						);
					}
				}
			}
		}
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
			
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('home')->withFlashMessage($message);
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
		$data = array(
			'odobrio_id'    	=>  $odobrio_user->id,			
			'razlog'  			=> "direktno odobrenje sa portala!",
			'datum_odobrenja'	=>  date_format($datum,'Y-m-d')
		);

		if($odobrio_user->id == $nadredjeni_uprava_id || Sentinel::inRole('odobrenja_uprave') ) {
			$data += ['odobreno' => "DA"];
		} else if($odobrio_user->id == $nadredjeni_voditelj_id ) {
			$data += ['odobreno2' => "DA"]; 
		} else {
			$data += ['odobreno' => "DA"];
		}
		
		$vacationRequest->updateVacationRequest($data);
		//send mail
		$odobrenje = 'je odobren';

		if( $vacationRequest->zahtjev == 'GO'){
			$zahtjev2 = 'korištenje godišnjeg odmora';			
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
		}
		if($vacationRequest->napomena) {
			$zahtjev2 .= ' (' . $vacationRequest->napomena . ')';
		}
		
		$employee = $vacationRequest->employee;
		$ime = $employee->first_name . ' ' . $employee->last_name;
		$work = $vacationRequest->employee->work;   					//radno mjesto zaposlenika
		$department = $vacationRequest->employee->work->department; 	// odjel kojem pripada radno mjesto
		$department_nadredjeni = $department->employee; 			 	// nadređeni odjela
		$work_nadredjeni = $work->nadredjeni;    						// glavni nadređeni radnog mjesta - član uprave
		$work_voditelj = $work->prvi_nadredjeni;    					// voditelj radnog mjesta
		$email_work_nadredjeni = $work_nadredjeni->email;				// email glavnog nadređenog
		$superior_mail = $vacationRequest->employee->superior['email'];   	//mail nadređenog djelatnika
		$employee_mail = $vacationRequest->employee['email'];
		
		$uprava = array('zeljko.rendulic@duplico.hr','durdica.rendulic@duplico.hr','ivan.rendulic@duplico.hr','nikola.rendulic@duplico.hr','matija.rendulic@duplico.hr');
		$mail_to = array($employee_mail, $email_work_nadredjeni, $superior_mail,'pravni@duplico.hr','jelena.juras@duplico.hr');

		$mails = array_diff( array_unique(array_merge($uprava, $mail_to)), array( $odobrio_user->email )); // svi mailovi uprava, djelatnik i voditelj - bez duplih, bez onog tko je odobrio

		if($odobrio_user->id == $nadredjeni_uprava_id || Sentinel::inRole('odobrenja_uprave') ) {
			// konačna potvrda uprave! - šalje se na upravu, voditelja i zaposlenika
			foreach($mails as $mail) {
				if($mail != '' && $mail != null) {
					Mail::queue(
						'email.zahtjevOD_uprave',
						['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $vacationRequest->razlog, 'odobrio' => $odobrio, 'ime' => $ime],
						function ($message) use ($mail, $employee) {
							$message->to($mail)
								->from('info@duplico.hr', 'Duplico')
								->subject('Odobrenje uprave - ' .  $employee->first_name . ' ' . $employee->last_name);
						}
					);
				}
			}
		} else {
			// odobrio voditelj, šalje se na nadređenog iz uprave
			if($email_work_nadredjeni) {
				Mail::queue(
					'email.zahtjevOD',
					['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=>  $vacationRequest->razlog, 'odobrio' => $odobrio, 'ime' => $ime],
					function ($message) use ($email_work_nadredjeni, $employee) {
						$message->to($email_work_nadredjeni)
							->from('info@duplico.hr', 'Duplico')
							->subject('Odobrenje zahtjeva - ' .  $employee->first_name . ' ' . $employee->last_name);
					}
				);
			}
			$ja = 'jelena.juras@duplico.hr';
			Mail::queue(
				'email.zahtjevOD',
				['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=>  $vacationRequest->razlog, 'odobrio' => $odobrio, 'ime' => $ime],
				function ($message) use ($ja, $employee) {
					$message->to($ja)
						->from('info@duplico.hr', 'Duplico')
						->subject('Odobrenje zahtjeva - ' .  $employee->first_name . ' ' . $employee->last_name);
				}
			);
		}
		$message = session()->flash('success', 'Zahtjev je odobren i poslan je mail');
		return redirect()->back();
	}

	public function storeConf(Request $request)
    {
		try {
			$input = $request->except(['_token']);
			$vacationRequest = VacationRequest::find($_GET['id']);
			
			if(! $vacationRequest) {
				$message = session()->flash('error', 'Nešto je pošlo krivo, zahtjev nije nađen u bazi, javite se administratoru portala');
			
				return redirect()->route('home')->withFlashMessage($message);
			}
			$user = Sentinel::getUser(); 						// prijavljena osoba - odobrava
			$odobrio_user = Employee::where('employees.first_name', $user->first_name)->where('employees.last_name', $user->last_name)->first(); // prijavljeni djelatnik - odobrava
			$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name ;
			/*
					if($vacationRequest->odobreno == "DA") {
						return view('admin.confirmation_show', ['employee' => $odobrio_user->id, 'vacationRequest_id' => $vacationRequest->id,  'vacationRequest' => $vacationRequest ]);
					}
			*/
			$employee_id = $vacationRequest->employee_id;
			$employee = Registration::where('employee_id', $employee_id)->first();    // djelatnik koji je poslao zahtjev 
			
			$ime = $employee->employee['first_name'] . ' ' . $employee->employee['last_name'];
			$employee_mail = $employee->employee['email'];
						
			$datum = new DateTime('now');
			
			$data = array(
				'odobrio_id'    	=>  $odobrio_user->id,
				'razlog'  			=>  $_GET['razlog'],
				'datum_odobrenja'	=>  date_format($datum,'Y-m-d')
			);
			
			if( isset($input['uprava'])) {
				if(isset($_GET['odobreno'])) {
					$data += ['odobreno' => $_GET['odobreno']];  // odobrenje uprave
				} else {
					$data += ['odobreno' => $_GET['odobreno2']];  // odobrenje uprave
				}
			} else {
				if(isset($_GET['odobreno2'])){
					$data += ['odobreno2' => $_GET['odobreno2']]; // odobrenje voditelja
				} else {
					$data += ['odobreno2' => $_GET['odobreno']]; // odobrenje voditelja
				}
			}

			$vacationRequest->updateVacationRequest($data);
			
			if($input['email'] == 'DA' ){ 
				if( ( isset($input['odobreno']) && $input['odobreno'] == 'DA') || ( isset($input['odobreno2']) && $input['odobreno2'] == 'DA' ) ) {
					$odobrenje = 'je odobren';
				} else {
					$odobrenje = 'nije odobren';
				}
				
				if( $vacationRequest->zahtjev == 'GO'){
					$zahtjev2 = 'korištenje godišnjeg odmora';			
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
				}

				if($vacationRequest->napomena) {
					$zahtjev2 .= ' (' . $vacationRequest->napomena . ')';
				}
				
				$work = Work::where('id', $employee->radnoMjesto_id)->first();   //radno mjesto zaposlenika
				$department = $work->department; 							// odjel kojem pripada radno mjesto
				$department_nadredjeni = $department->employee; 			 // nadređeni odjela
				$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta - član uprave
				$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
				$email_work_nadredjeni = $work_nadredjeni->email;				// email glavnog nadređenog
				$superior_mail = $employee->superior['email'];   //mail nadređenog djelatnika

				$uprava = array('zeljko.rendulic@duplico.hr','durdica.rendulic@duplico.hr','ivan.rendulic@duplico.hr','nikola.rendulic@duplico.hr','matija.rendulic@duplico.hr');
				$mail_to = array($employee_mail, $email_work_nadredjeni, $superior_mail,'pravni@duplico.hr','jelena.juras@duplico.hr');

				$mails = array_diff( array_unique(array_merge($uprava, $mail_to)), array( $odobrio_user->email )); // svi mailovi uprava, djelatnik i voditelj - bez duplih, bez onog tko je odobrio

				if(isset($input['uprava'])) {
					// konačna potvrda uprave! - šalje se na upravu, voditelja i zaposlenika

					foreach($mails as $mail) {
						if($mail != '' && $mail != null) {
							Mail::queue(
								'email.zahtjevOD_uprave',
								['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $_GET['razlog'], 'odobrio' => $odobrio, 'ime' => $ime],
								function ($message) use ($mail, $employee) {
									$message->to($mail)
										->from('info@duplico.hr', 'Duplico')
										->subject('Odobrenje uprave - ' .  $employee->employee['first_name'] . ' ' . $employee->employee['last_name']);
								}
							);
						}
					}
				} else {
					// odobrio voditelj, šalje se na nadređenog iz uprave
					if($email_work_nadredjeni) {
						Mail::queue(
							'email.zahtjevOD',
							['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $_GET['razlog'], 'odobrio' => $odobrio, 'ime' => $ime],
							function ($message) use ($email_work_nadredjeni, $employee) {
								$message->to($email_work_nadredjeni)
									->from('info@duplico.hr', 'Duplico')
									->subject('Odobrenje zahtjeva - ' .  $employee->employee['first_name'] . ' ' . $employee->employee['last_name']);
							}
						);
					}
					$ja = 'jelena.juras@duplico.hr';
						Mail::queue(
							'email.zahtjevOD',
							['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $_GET['razlog'], 'odobrio' => $odobrio, 'ime' => $ime],
							function ($message) use ($ja, $employee) {
								$message->to($ja)
									->from('info@duplico.hr', 'Duplico')
									->subject('Odobrenje zahtjeva - ' .  $employee->employee['first_name'] . ' ' . $employee->employee['last_name']);
							}
						);
				}
			}
			
			$message = session()->flash('success', 'Zahtjev je odobren');
			
			return redirect()->route('home')->withFlashMessage('Zahtjev je odobren');
		} catch (\Throwable $th) {
			$message = session()->flash('error', 'Nešto je pošlo krivo, javite se administratoru portala');
			
			return redirect()->route('home')->withFlashMessage($message);
		}
		
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
		}elseif($vacationRequest->zahtjev == 'Bolovanje') {
			$zahtjev = 'bolovanje';
		}elseif($vacationRequest->zahtjev == 'Izlazak'){
			$zahtjev = 'izlazak';
		}elseif($vacationRequest->zahtjev == 'SLD'){
			$zahtjev = 'slobodan dan';
		}elseif($vacationRequest->zahtjev == 'PL'){
			$zahtjev = 'plaćeni dopust';
		}elseif($vacationRequest->zahtjev == 'NPL'){
			$zahtjev = 'neplaćeni dopust';
		}elseif($vacationRequest->zahtjev == 'VIK'){
			$zahtjev = 'slobodan vikend';
		}
		
		$dani = array('GOpocetak' =>$vacationRequest->GOpocetak, 'GOzavršetak' =>$vacationRequest->GOzavršetak);
		$daniGO = $this->daniGO($dani); //vraća dane zahtjeva

		return view('admin.VacationRequest', ['vacationRequest' => $vacationRequest, 'zahtjev' => $zahtjev, 'daniGO' => $daniGO]);
	}
	
	public function AllVacationRequest(Request $request)
	{
		$datum = explode('-',$request['mjesec']);
		$godina = $datum[0];
        $mjesec = $datum[1];

		$vacationRequests = VacationRequest::where('odobreno','DA')->whereMonth('GOpocetak',$mjesec)->whereYear('GOpocetak',$godina)->get();
		
		return view('admin.AllVacationRequest', ['vacationRequests' => $vacationRequests,'mjesec' => $mjesec,'godina' => $godina]);
	}
}
