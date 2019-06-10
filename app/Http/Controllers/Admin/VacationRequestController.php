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
		
		//$employee = Employee::where('id',45)->first();
		$registration = Registration::where('registrations.employee_id', $employee->id)->first();
		
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		$razmjeranGO = $this->razmjeranGO($registration);
		$razmjeranGO_PG = $this->razmjeranGO_PG($registration);
		
		$daniZahtjevi = $this->daniZahtjevi($registration);
		$daniZahtjevi_PG = $this->daniZahtjeviPG($registration);
		$slobodni_dani = $this->prekovremeni_bez_izlazaka($registration);
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($registration);
		
		return view('admin.vacation_requests.create')->with('registration', $registration)->with('registrations', $registrations)->with('employee', $employee)->with('daniZahtjevi', $daniZahtjevi)->with('daniZahtjevi_PG', $daniZahtjevi_PG)->with('slobodni_dani', $slobodni_dani )->with('koristeni_slobodni_dani', $koristeni_slobodni_dani)->with('razmjeranGO', $razmjeranGO)->with('razmjeranGO_PG', $razmjeranGO_PG );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Vacation_RequestRequest $request)
    {
		$datum = new DateTime('now');    /* današnji dan */
		$mj_danas = $datum->format('m');
		$dan_danas = $datum->format('d');
		$datum_Pocetak = new DateTime($request->GOpocetak);
		$dan_pocetak = $datum_Pocetak->format('d');
		$mj_pocetak = $datum_Pocetak->format('m');
		
		$input = $request->except(['_token']);
		if($input['GOzavršetak'] == '' ){
			$input['GOzavršetak'] = $input['GOpocetak'];
		}
		if($input['employee_id'] == 'svi'){
			$registrations = Registration::get();
			foreach($registrations as $registration){
				if(! EmployeeTermination::where('employee_id',$registration->employee_id)->first() ){
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
			}
		} elseif(is_array($input['employee_id']) && count($input['employee_id'])>1){
			foreach($input['employee_id'] as $employee_id){
				$user = Registration::where('employee_id',$input['employee_id'] );
				$data = array(
				'zahtjev'  			=> $input['zahtjev'],
				'employee_id'  		=> $employee_id,
				'GOpocetak'    		=> date("Y-m-d", strtotime($input['GOpocetak'])),
				'GOzavršetak'		=> date("Y-m-d", strtotime($input['GOzavršetak'])),
				'vrijeme_od'  		=> $input['vrijeme_od'],
				'vrijeme_do'  		=> $input['vrijeme_do'],
				'napomena'  		=> $input['napomena'],
				'odobreno' 			=> 'DA',
				'odobrio_id' 		=> '58'
				);
				
				$vacationRequest = new VacationRequest();
				$vacationRequest->saveVacationRequest($data);
			}

		} else {
			$user = Registration::where('employee_id', $input['employee_id'] )->first();
			
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
			$koristeni_slobodni_dani = $this->koristeni_slobodni_dani($user);/* računa iskorištene slobodne dane */
			
			$ukupnoDani = $this->ukupnoDani($zahtjev); //vraća dane zahtjeva
			
			$razlika_SLD = $slobodni_dani - $ukupnoDani;

			if(!Sentinel::inRole('administrator') && $input['zahtjev'] == 'GO' && $razlika_dana < 0  ){   //&& $user->work['job_description'] != 'montaža'
				$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_dana . ' dana');
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
				
				$employee = Employee::where('employees.id',$user->employee_id)->first();
				
				if($input['email'] == 'DA' ){
					$work = Work::where('id', $user->radnoMjesto_id)->first();
					$nadredjeni = $work->nadredjeni;
					$prvi_nadredjeni = $work->prvi_nadredjeni;
					
					$prvi_nadredjeni_mail = null;
					$drugi_nadredjeni_mail = null;
					$nadredjeni_mail = null;
					if($nadredjeni) {
						$nadredjeni_mail = 	$nadredjeni->email;
					}
					if($prvi_nadredjeni) {
						$prvi_nadredjeni_mail = $prvi_nadredjeni->email;
					}
					$drugi_nadredjeni = $work->drugi_nadredjeni;
					if($drugi_nadredjeni) {
						$drugi_nadredjeni_mail = $drugi_nadredjeni->email;
					}
					$mail_to = array_unique(array($prvi_nadredjeni_mail, $nadredjeni_mail, $drugi_nadredjeni_mail, 'jelena.juras@duplico.hr'));
					//$mail_to = array('jelena.juras@duplico.hr');
					
					foreach($mail_to as $email_to){
						if(isset($email_to)){
							Mail::queue(
								'email.zahtjevGO',
								['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani, 'koristeni_slobodni_dani' => $koristeni_slobodni_dani],
								function ($message) use ($email_to, $employee) {
									$message->to($email_to)
										->from('info@duplico.hr', 'Duplico')
										->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
								}
							);
						}
					}
				}
			}
		}
		$message = session()->flash('success', 'Zahtjev je poslan');
			
		//return redirect()->back()->withFlashMessage($messange);
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
		
		$registration = Registration::where('registrations.employee_id',  $employee->id)->first();
		
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;
			
		return view('admin.vacation_requests.show', ['vacationRequests' => $vacationRequests,'afterHours' => $afterHours ])->with('employee', $employee)->with('ova_godina', $ova_godina)->with('prosla_godina', $prosla_godina);
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
		
		$datum = new DateTime('now');    /* današnji dan */
		$datum_Pocetak = new DateTime($request->GOpocetak);
		$mj_danas = $datum->format('m');
		$dan_danas = $datum->format('d');
		$dan_pocetak = $datum_Pocetak->format('d');
		$mj_pocetak = $datum_Pocetak->format('m');
		
		
		$input = $request->except(['_token']);
	
		if($input['GOzavršetak'] == '' ){
			$input['GOzavršetak'] = $input['GOpocetak'];
		}
	
		$user = Registration::where('employee_id', $input['employee_id'] )->first();

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
				$work = Work::where('id', $user->radnoMjesto_id)->first();
				$nadredjeni = $work->nadredjeni;
				$prvi_nadredjeni_mail = null;
				$nadredjeni_mail = null;
				if($nadredjeni) {
					$nadredjeni_mail = 	$nadredjeni->email;
				}
				$prvi_nadredjeni = $work->prvi_nadredjeni;
				if($prvi_nadredjeni) {
					$prvi_nadredjeni_mail = $prvi_nadredjeni->email;
				}

				$mail_to = array($prvi_nadredjeni_mail, $nadredjeni_mail,'jelena.juras@duplico.hr');
				
				
				foreach($mail_to as $email_to){
					Mail::queue(
						'email.zahtjevGO',
						['employee' => $employee,'vacationRequest' => $vacationRequest,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani],
						function ($message) use ($email_to, $employee) {
							$message->to($email_to)
								->from('info@duplico.hr', 'Duplico')
								->subject('Ispravak zahtjeva- ' .  $employee->first_name . ' ' .  $employee->last_name);
						}
					);
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
        

        // Return the appropriate response
		return redirect()->route('admin.vacation_requests.storeConf');

    }
		
	public function storeConf(Request $request)
    {
		$input = $request->except(['_token']);
		$vacationRequest = VacationRequest::find($_GET['id']);
		
		$user = Sentinel::getUser();
		$odobrio_user = Employee::where('employees.first_name', $user->first_name)->where('employees.last_name', $user->last_name)->first();
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name ;

		$employee_id = $vacationRequest->employee_id;
		$employee = Employee::where('employees.id', $employee_id)->first();
		$ime = $employee->first_name . ' ' . $employee->last_name;
		$mail = $employee->email;
		
		$uprava = 'uprava@duplico.hr';
				
		$datum = new DateTime('now');

		$data = array(
			'odobreno'  		=>  $_GET['odobreno'],
			'odobrio_id'    	=>  $odobrio_user->id,
			'razlog'  			=>  $_GET['razlog'],
			'datum_odobrenja'	=>  date_format($datum,'Y-m-d')
		);
		
		$vacationRequest->updateVacationRequest($data);
		
		if($input['odobreno'] == 'DA'){
			$odobrenje = 'je odobren';
		} else {
			$odobrenje = 'nije odobren';
		}
		
		if($vacationRequest->zahtjev == 'GO'){
			$zahtjev2 = 'korištenje godišnjeg odmora';			
		}elseif($vacationRequest->zahtjev == 'Bolovanje') {
			$zahtjev2 = 'bolovanje';
		}elseif($vacationRequest->zahtjev == 'Izlazak'){
			$zahtjev2 = 'izlazak';
		}elseif($vacationRequest->zahtjev == 'SLD'){
			$zahtjev2 = 'slobodan dan';
		}elseif($vacationRequest->zahtjev == 'PL'){
			$zahtjev2 = 'plaćeni dopust';
		}elseif($vacationRequest->zahtjev == 'NPL'){
			$zahtjev2 = 'neplaćeni dopust';
		}elseif($vacationRequest->zahtjev == 'VIK'){
			$zahtjev2 = 'slobodan vikend';
		}
		
		Mail::queue(
			'email.zahtjevOD',
			['employee' => $employee,'vacationRequest' => $vacationRequest,'mail' => $mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $_GET['razlog'], 'odobrio' => $odobrio, 'ime' => $ime],
			function ($message) use ($mail, $employee) {
				$message->to($mail)
					->from('info@duplico.hr', 'Duplico')
					->subject('Odobrenje zahtjeva');
			}
		);
		
		Mail::queue(
			'email.zahtjevOD2',
			['employee' => $employee,'vacationRequest' => $vacationRequest,'mail' => $mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $_GET['razlog'], 'odobrio' => $odobrio, 'ime' => $ime],
			function ($message) use ($uprava, $employee) {
				$message->to($uprava)
					->from('info@duplico.hr', 'Duplico')
					->subject('Odobrenje zahtjeva');
			}
		);

		$proba = array('jelena.juras@duplico.hr');
		
		Mail::queue(
			'email.zahtjevOD',
			['employee' => $employee,'vacationRequest' => $vacationRequest,'mail' => $mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $_GET['razlog'], 'odobrio' => $odobrio, 'ime' => $ime],
			function ($message) use ($proba, $employee) {
				$message->to($proba)
					->from('info@duplico.hr', 'Duplico')
					->subject('Odobrenje zahtjeva');
			}
		);
		
		$message = session()->flash('success', 'Zahtjev je odobren');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('home')->withFlashMessage('Zahtjev je odobren');
    }
	
	public function confirmation_show(Request $request)
	{
		return view('admin.confirmation_show')->with('vacationRequest_id', $request->vacationRequest_id);
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
		$godina = substr( $request['mjesec'],'-4');
		$mjesec = strstr( $request['mjesec'],"-",true);

		$vacationRequests = VacationRequest::where('odobreno','DA')->whereMonth('GOpocetak',$mjesec)->whereYear('GOpocetak',$godina)->get();
		
		return view('admin.AllVacationRequest', ['vacationRequests' => $vacationRequests,'mjesec' => $mjesec,'godina' => $godina]);
	}
}
