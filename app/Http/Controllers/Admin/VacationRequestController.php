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
use App\Models\Works;
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
		
		$dani_GO = $this->godišnji($employee);
		$slobodni_dani = $this->slobodni_dani($employee);
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($employee);
		
		return view('admin.vacation_requests.create')->with('registration', $registration)->with('registrations', $registrations)->with('employee', $employee)->with('dani_GO', $dani_GO)->with('slobodni_dani', $slobodni_dani )->with('koristeni_slobodni_dani', $koristeni_slobodni_dani );
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
						'napomena'  		=> $input['napomena']
					);

					$vacationRequest = new VacationRequest();
					$vacationRequest->saveVacationRequest($data);
				}
			}
		} else {
			$user =  Employee::where('id',$input['employee_id'])->first();
		
			$dani_GO = $this->godišnji($user); //vraća dane godišnjeg za usera -  nalazi se i u input ['Dani'])
			$zahtjev = array('GOpocetak' =>$input['GOpocetak'], 'GOzavršetak' =>$input['GOzavršetak']);
			$dani_zahtjev = $this->daniGO($zahtjev); //vraća dane zahtjeva
			$razlika_dana =  $dani_GO - $dani_zahtjev;
			
			$slobodni_dani = $this->slobodni_dani($user); /* računa broj slobodnih dana prema prekovremenim satima */
			$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($user);/* računa iskorištene slobodne dane */
			
			$ukupnoDani = $this->ukupnoDani($zahtjev); //vraća dane zahtjeva
			$razlika_SLD = $slobodni_dani - $ukupnoDani;
			
			//dd($razlika_SLD);
			if($input['zahtjev'] == 'GO' && $razlika_dana < 0 ){
				$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_dana . ' dana');
				return redirect()->back()->withFlashMessage($message);
			} elseif($input['zahtjev'] == 'SLD' && $razlika_SLD < 0 ){
				$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_SLD . ' dana');
				return redirect()->back()->withFlashMessage($message);
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

				$vacationRequest = new VacationRequest();
				$vacationRequest->saveVacationRequest($data);
				
		//prijavljena osoba		
				$user = Sentinel::getUser();
				$employee = Employee::where('employees.id',$input['employee_id'])->first();
				$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $input['employee_id'])->select('registrations.*','works.*')->first();
				
				$nadređeni = Employee::where('employees.id',$registration->user_id)->value('email');
				$prvi_nadređeni = Employee::where('employees.id',$registration->prvi_userId)->value('email');
				
				$nadređeni1 = $registration->user_id; // id nadređene osobe
				
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
				}
				
				$proba = 'jelena.juras@duplico.hr';
				$uprava = 'uprava@duplico.hr';
				
				$nadređene_osobe =array();
				array_push($nadređene_osobe, $nadređeni);
				if($prvi_nadređeni){
					array_push($nadređene_osobe, $prvi_nadređeni, 'jelena.juras@duplico.hr');
				}
				
				Mail::queue(
					'email.zahtjevGO',
					['employee' => $employee,'vacationRequest' => $vacationRequest,'nadređene_osobe' => $nadređene_osobe,'dani_GO' => $dani_GO ,'napomena' => $input['napomena'],'nadređeni1' => $nadređeni1,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani],
					function ($message) use ($proba, $employee) {
						$message->to($proba)
							->from('info@duplico.hr', 'Duplico')
							->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
				
				Mail::queue(
						'email.zahtjevGO',
						['employee' => $employee,'vacationRequest' => $vacationRequest,'nadređene_osobe' => $nadređene_osobe,'nadređeni1' => $nadređeni1,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme,'dani_GO' => $dani_GO,'dani_zahtjev' => $dani_zahtjev, 'GOzavršetak' => $input['GOzavršetak'], 'slobodni_dani' => $slobodni_dani ],
						function ($message) use ($uprava, $employee) {
							$message->to($uprava)
								->from('info@duplico.hr', 'Duplico')
								->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
						}
				);
				
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
		
		
		$vacationRequests = VacationRequest::where('employee_id', $employee->id)->get();
		
		$registration = Registration::where('registrations.employee_id',  $employee->id)->first();
		
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;
			
		return view('admin.vacation_requests.show', ['vacationRequests' => $vacationRequests])->with('employee', $employee)->with('ova_godina', $ova_godina)->with('prosla_godina', $prosla_godina);
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
		//$user = Sentinel::getUser();
		
		$employee = Employee::where('employees.id',$vacationRequest->employee_id)->first();
		$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $employee->id)->select('registrations.*','works.*')->first();
		
		return view('admin.vacation_requests.edit', ['vacationRequest' => $vacationRequest])->with('registration', $registration)->with('employee', $employee);
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
			
		$employee = Employee::where('employees.id',$vacationRequest->employee_id)->first();
		$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id',$employee->id)->select('registrations.*','works.*')->first();
		
		$dani_GO = $this->godišnji($employee); //vraća dane godišnjeg za usera -  nalazi se i u input ['Dani'])
		$zahtjev = array('GOpocetak' =>$input['GOpocetak'], 'GOzavršetak' =>$input['GOzavršetak']);
		$dani_zahtjev = $this->daniGO($zahtjev); //vraća dane zahtjeva
		
		$razlika_dana =  $dani_GO - $dani_zahtjev;
		
		if($razlika_dana < 0 ){
			$message = session()->flash('error', 'Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od neiskorištenih dana za ' . -$razlika_dana . ' dana');
			return redirect()->back()->withFlashMessage($message);
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
			
			$vacationRequest->updateVacationRequest($data);
			
			$nadređeni = Employee::where('employees.id',$registration->user_id)->value('email');
			$prvi_nadređeni = Employee::where('employees.id',$registration->prvi_userId)->value('email');
			
			$nadređeni1 = $registration->user_id;
			
			$proba = array('jelena.juras@duplico.hr');
			$uprava = 'uprava@duplico.hr';
			
			$nadređene_osobe =array();
			array_push($nadređene_osobe, $nadređeni);
			if($prvi_nadređeni){
				array_push($nadređene_osobe, $prvi_nadređeni,'jelena.juras@duplico.hr');
			}
		
			if($input['zahtjev'] == 'GO'){
				$zahtjev2 = 'korištenje godišnjeg odmora';
				$vrijeme="";
			}elseif($input['zahtjev'] == 'Izlazak') {
				$zahtjev2 = 'prijevremeni izlaz';
				$vrijeme = 'od ' . $input['vrijeme_od'] . ' do ' . $input['vrijeme_do']; 
			}elseif($input['zahtjev'] == 'Bolovanje'){
				$zahtjev2 = 'bolovanje';
				$vrijeme="";
			}
			
			/*if($nadređeni){
				Mail::queue(
					'email.zahtjevGO',
					['employee' => $employee,'vacationRequest' => $vacationRequest,'nadređene_osobe' => $nadređene_osobe,'nadređeni1' => $nadređeni1,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme,'dani_zahtjev' => $dani_zahtjev, 'dani_GO' => $dani_GO],
					function ($message) use ($nadređeni, $employee) {
						$message->to($nadređeni)
							->from('info@duplico.hr', 'Duplico')
							->subject('Ispravak zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
			}
			if($prvi_nadređeni){
				Mail::queue(
					'email.ObavijestGO',
					['employee' => $employee,'vacationRequest' => $vacationRequest,'nadređene_osobe' => $nadređene_osobe,'nadređeni1' => $nadređeni1,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme,'dani_zahtjev' => $dani_zahtjev, 'dani_GO' => $dani_GO ],
					function ($message) use ($prvi_nadređeni, $employee) {
						$message->to($prvi_nadređeni)
							->from('info@duplico.hr', 'Duplico')
							->subject('Ispravak zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
			}*/
			
			Mail::queue(
				'email.zahtjevGO',
				['employee' => $employee,'vacationRequest' => $vacationRequest,'nadređene_osobe' => $nadređene_osobe,'nadređeni1' => $nadređeni1,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme,'dani_zahtjev' => $dani_zahtjev, 'dani_GO' => $dani_GO  ],
				function ($message) use ($proba, $employee) {
					$message->to($proba)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
			Mail::queue(
				'email.zahtjevGO',
				['employee' => $employee,'vacationRequest' => $vacationRequest,'nadređene_osobe' => $nadređene_osobe,'nadređeni1' => $nadređeni1,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme,'dani_zahtjev' => $dani_zahtjev, 'dani_GO' => $dani_GO  ],
				function ($message) use ($uprava, $employee) {
					$message->to($uprava)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
			
			/*foreach($nadređene_osobe as $key => $nadređena_osoba){
				Mail::queue(
				'email.zahtjevGO',
				['employee' => $employee,'vacationRequest' => $vacationRequest,'nadređene_osobe' => $nadređene_osobe,'nadređeni1' => $nadređeni1,'napomena' => $input['napomena'],'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme ],
				function ($message) use ($nadređena_osoba, $employee) {
					$message->to($nadređena_osoba)
						->from('info@duplico.hr', 'Duplico')
						->subject('Ispravak zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);*/
		}
			
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
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
		
		$employee_id = $vacationRequest->employee_id;
		$employee = Employee::where('employees.id', $employee_id)->first();
		$mail = $employee->email;
		
		$uprava = 'uprava@duplico.hr';
		
		$data = array(
			'odobreno'  		=>  $_GET['odobreno'],
			'odobrio_id'    	=> $_GET['user_id'],
			'razlog'  		=>  $_GET['razlog'],
			'datum_odobrenja'	=> date("Y-m-d", strtotime($_GET['datum_odobrenja']))
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
		}
		
		$user = Sentinel::getUser();
		$odobrio = $user->first_name . ' ' . $user->last_name ;
		$ime = $employee->first_name . ' ' . $employee->last_name;
		
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
			'email.zahtjevOD2',
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

}
