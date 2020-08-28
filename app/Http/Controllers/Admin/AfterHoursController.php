<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AfterHour;
use App\Models\Employee;
use App\Models\Registration;
use App\Models\Project;
use App\Http\Requests\AfterHourRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GodisnjiController;
use Sentinel;
use Mail;
use DateTime;

class AfterHoursController extends GodisnjiController
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
		$registration = Registration::where('employee_id', $employee->id)->first();
		$slobodni_dani = $this->slobodni_dani($registration); /* računa broj slobodnih dana prema prekovremenim satima */
		$koristeni_slobodni_dani = $this->koristeni_slobodni_dani($registration);/* računa iskorištene slobodne dane */
		
		if(Sentinel::inRole('administrator')){
			$afterHours = AfterHour::join('employees','after_hours.employee_id','employees.id')->select('after_hours.*','employees.first_name', 'employees.last_name')->whereYear('datum',date('Y'))->orderBy('employees.last_name','ASC')->orderBy('datum','DESC')->get();
			if(date('m') < 3) {
				$afterHours =  $afterHours->merge(AfterHour::join('employees','after_hours.employee_id','employees.id')->select('after_hours.*','employees.first_name', 'employees.last_name')->whereYear('datum',date('Y')-1)->whereMonth('datum','>',10)->orderBy('employees.last_name','ASC')->orderBy('datum','DESC')->get());
			}
		} else {
			$afterHours = AfterHour::where('employee_id',$employee->id)->where('odobreno','')->orderBy('datum','DESC')->orderBy('employees.last_name','ASC')->get();
		}
		$months = $this->months_afterHour();
	
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('employees.first_name','employees.last_name')->where('odjava', null)->orderBy('employees.last_name','ASC')->get();
		
		return view('admin.afterHours.index',['afterHours'=>$afterHours,'registration'=>$registration,'employee'=>$employee,'slobodni_dani'=>$slobodni_dani,'koristeni_slobodni_dani'=>$koristeni_slobodni_dani,'months'=>$months,'registrations'=>$registrations]);
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
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.employee_id','employees.first_name','employees.last_name')->where('odjava', null)->orderBy('employees.last_name','ASC')->get();
		$projects = Project::where('active',1)->get();

		return view('admin.afterHours.create',['employee'=> $employee,'projects'=>$projects,'registrations'=> $registrations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token']);
		
		if(is_array($input['employee_id']) && count($input['employee_id'])>0) {
			foreach($input['employee_id'] as $employee_id){
				$data = array(
					'employee_id'  		=> $employee_id,
					'project_id'  		=> $input['project_id'],
					'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
					'start_time'  		=> $input['start_time'],
					'end_time'  		=> $input['end_time'],
					'napomena'  		=> $input['napomena']
				);
				
				$afterHour = new AfterHour();
				$afterHour->saveAfterHour($data);

				$employee = Employee::where('employees.id', $afterHour->employee_id)->first();
				$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $afterHour->employee_id)->select('registrations.*','works.*')->first();
				
				$nadredjeni1 = $registration->work->nadredjeni; //  nadređena osobe - iz uprave
				$nadredjeni_voditelj = $registration->work->prvi_nadredjeni; //  nadređena osobe - voditelj
				$drugi_voditelj = $registration->work->drugi_nadredjeni;   //postavljeno za prekovremene za IT 
				
				$emails = array('jelena.juras@duplico.hr', 'uprava@duplico.hr');
		
				$email ='uprava@duplico.hr';

				$vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
				$time1 = new DateTime($input['start_time'] );
				$time2 = new DateTime($input['end_time']);
				
				$interval = $time2->diff($time1);
				$interval = $interval->format('%H:%I');
				
				try {
					Mail::queue(
						'email.zahtjevAfterHour',
						['employee' => $employee,'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'vrijeme' => $vrijeme, 'interval' => $interval ],
						function ($message) use ($employee, $email) {
							$message->to($email)
								->from('info@duplico.hr', 'Duplico')
								->subject('Zahtjev za odobrenje prekovremenog rada - ' .  $employee->first_name . ' ' .  $employee->last_name);
						}
					);
				
					if ( isset($drugi_voditelj) && $drugi_voditelj->employee['last_name'] == 'Novosel') {
						Mail::queue(
							'email.zahtjevAfterHour_info',
							['employee' => $employee,'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'vrijeme' => $vrijeme, 'interval' => $interval ],
							function ($message) use ($employee, $nadredjeni_voditelj) {
								$message->to($nadredjeni_voditelj->email)
										->from('info@duplico.hr', 'Duplico')										
										->subject('Zahtjev za odobrenje prekovremenog rada - ' .  $employee->first_name . ' ' .  $employee->last_name);
							}
						);
					}
					
				} catch (Exception $e) {
					$message = session()->flash('error', 'Mail nije poslan, došlo je do problema.');
				
					return redirect()->back()->withFlashMessage($message);
				} 
			}
		} else {
			$request_exist = GodisnjiController::afterHour_for_request($input['employee_id'], $input['datum'], $input['start_time'], $input['end_time'] );
			
			if(! Sentinel::inRole('administrator') && $request_exist == false) {
				$message = session()->flash('error', 'Zahtjev za taj dan već postoji, nije moguće poslati zahtjev');    /*  AKO ZAHTJEV VEĆ POSTOJI VRATI PORUKU  */
				return redirect()->back()->withFlashMessage($message);
			} else { 
				$data = array(
					'employee_id'  		=> $input['employee_id'],
					'project_id'  		=> $input['project_id'],
					'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
					'start_time'  		=> $input['start_time'],
					'end_time'  		=> $input['end_time'],
					'napomena'  		=> $input['napomena']
				);
				$afterHour = new AfterHour();
				$afterHour->saveAfterHour($data);
			
				$employee = Employee::where('employees.id', $input['employee_id'])->first();
				$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $input['employee_id'])->select('registrations.*','works.*')->first();
				$employee_mail = $employee->email;
				
				/* $nadredjeni = Employee::where('employees.id',$registration->user_id)->value('email');  */// nadređeni iz uprave
				$nadredjeni1 = $registration->work->nadredjeni; //  nadređena osobe - iz uprave
				$nadredjeni_voditelj = $registration->work->prvi_nadredjeni; //  nadređena osobe - voditelj
				$drugi_voditelj = $registration->work->drugi_nadredjeni;   //postavljeno za prekovremene za IT 
				
				$emails = array('uprava@duplico.hr', 'jelena.juras@duplico.hr');

				$vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
				$time1 = new DateTime($input['start_time'] );
				$time2 = new DateTime($input['end_time']);
				
				$interval = $time2->diff($time1);
				$interval = $interval->format('%H:%I');
		
				try {
					foreach ($emails as $email) {
						Mail::queue(
							'email.zahtjevAfterHour',
							['employee' => $employee,'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'vrijeme' => $vrijeme, 'interval' => $interval ],
							function ($message) use ($employee, $email) {
								$message->to($email)
									->from('info@duplico.hr', 'Duplico')
									->subject('Zahtjev za odobrenje prekovremenog rada - ' .  $employee->first_name . ' ' .  $employee->last_name);
							}
						);
					}
					
					Mail::queue(
						'email.zahtjevAfterHour_send',
						['afterHour' => $afterHour ],
						function ($message) use ( $employee_mail ) {
							$message->to($employee_mail)
								->from('info@duplico.hr', 'Duplico')
								->subject('Zahtjev');
						}
					);

				} catch (Exception $e) {
					$message = session()->flash('error', 'Mail nije poslan, došlo je do problema.');
				
					return redirect()->back()->withFlashMessage($message);
				} 
				if ( isset($drugi_voditelj)) {
					Mail::queue(
						'email.zahtjevAfterHour_info',
						['employee' => $employee,'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'vrijeme' => $vrijeme, 'interval' => $interval ],
						function ($message) use ($employee, $nadredjeni_voditelj) {
							$message->to($nadredjeni_voditelj->email)
									->from('info@duplico.hr', 'Duplico')
									->subject('Zahtjev za odobrenje prekovremenog rada - ' .  $employee->first_name . ' ' .  $employee->last_name);
						}
					);
				}
			}
		}
		$message = session()->flash('success', 'Zahtjev je poslan');
				
		return redirect()->route('home')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $afterHour = AfterHour::find($id);
		$user = Sentinel::getUser();
		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		$projects = Project::where('active',1)->get();
		return view('admin.afterHours.edit', ['afterHour' => $afterHour, 'projects' => $projects, 'employee' => $employee]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AfterHourRequest $request, $id)
    {
        $afterHour = AfterHour::find($id);
		$input = $request->except(['_token']);
		
		$data = array(
			'employee_id'  		=> $input['employee_id'],
			'project_id'  		=> $input['project_id'],
			'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
			'start_time'  		=> $input['start_time'],
			'end_time'  		=> $input['end_time'],
			'napomena'  		=> $input['napomena']
		);
		$afterHour->updateAfterHour($data);
		
		$employee = Employee::where('employees.id', $input['employee_id'])->first();
		$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $input['employee_id'])->select('registrations.*','works.*')->first();
		
		$nadredjeni1 = $registration->work->nadredjeni; //  nadređena osobe - iz uprave
		$nadredjeni_voditelj = $registration->work->prvi_nadredjeni; //  nadređena osobe - voditelj
		$drugi_voditelj = $registration->work->drugi_nadredjeni;   //postavljeno za prekovremene za IT 
		
		$emails = array('jelena.juras@duplico.hr', 'uprava@duplico.hr');

		$email ='uprava@duplico.hr';
		
		$vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
		$time1 = new DateTime($input['start_time'] );
		$time2 = new DateTime($input['end_time']);
		
		$interval = $time2->diff($time1);
		$interval = $interval->format('%H:%I');
		
		try {
			 	Mail::queue(
					'email.zahtjevAfterHour',
					['employee' => $employee,'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'vrijeme' => $vrijeme, 'interval' => $interval ],
					function ($message) use ($employee, $email) {
						$message->to($email)
							->from('info@duplico.hr', 'Duplico')
							->subject('Zahtjev za odobrenje prekovremenog rada - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				); 
				Mail::queue(
					'email.zahtjevAfterHour',
					['employee' => $employee,'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'vrijeme' => $vrijeme, 'interval' => $interval ],
					function ($message) use ($employee, $email) {
						$message->to('jelena.juras@duplico.hr')
							->from('info@duplico.hr', 'Duplico')
							->subject('Zahtjev za odobrenje prekovremenog rada - ' .  $employee->first_name . ' ' .  $employee->last_name);
					}
				);
		} catch (Exception $e) {
			$message = session()->flash('error', 'Mail nije poslan, došlo je do problema.');
		
			return redirect()->back()->withFlashMessage($message);
		} 

		$message = session()->flash('success', 'Podaci su ispravljeni');
			
		return redirect()->route('admin.afterHours.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $afterHour = AfterHour::find($id);
		$afterHour->delete();
		
		$message = session()->flash('success', 'Zahtjev je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	public function storeConf(Request $request)
    {
		$input = $request->except(['_token']);
		$afterHour = AfterHour::find($input['id']);
		if($afterHour) {
			$employee = Employee::where('employees.id', $afterHour->employee_id)->first();
			$mail = $employee->email;
			
			$user = Sentinel::getUser();
			$odobrio = Employee::where('last_name',$user->last_name)->where('first_name',$user->first_name)->first();
	
			$data = array(
				'odobreno'  		=>  $input['odobreno'],
				'odobreno_h'  		=>  $input['odobreno_h'],
				'odobrio_id'    	=>  $odobrio ? $odobrio->id : null,
				'razlog'  			=>  $input['razlog'],
				'datum_odobrenja'	=>  date("Y-m-d")
			);
			
			$afterHour->updateAfterHour($data);
			
			if($input['odobreno'] == 'DA'){
				$odobrenje = 'je potvrđen';
			} else {
				$odobrenje = 'nije potvrđen';
			}
			
			try {
				Mail::queue(
					'email.zahtjevAfterHourOD',
					['employee' => $employee,'afterHour' => $afterHour,'mail' => $mail, 'odobrenje' => $odobrenje, 'razlog'=> $afterHour->razlog ],
					function ($message) use ($mail, $employee) {
						$message->to($mail)
							->from('info@duplico.hr', 'Duplico')
							->subject('Odobrenje zahtjeva');
					}
				);
			} catch (Exception $e) {
				$message = session()->flash('error', 'Mail nije poslan, došlo je do problema.');
			
				return redirect()->back()->withFlashMessage($message);
			}
				
			$message = session()->flash('success', 'Zahtjev je potvrđen');
		} else {
			$message = session()->flash('error', 'Zahtjev nije nađen');
		}
		return redirect()->route('home')->withFlashMessage($message);
    }

	public function confirmationAfter_show(Request $request)
	{
		$user = Sentinel::getUser();
		$nadredjeni1 = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		$afterHour = AfterHour::find($request->id);

		$time1 = new DateTime($afterHour->start_time );
		$time2 = new DateTime($afterHour->end_time );
		
		$interval = $time2->diff($time1);
		$interval = $interval->format('%H:%I');

		return view('admin.confirmationAfterHour')->with('afterHour', $afterHour)->with('nadredjeni1', $nadredjeni1->id)->with('interval', $interval);
	}


	/* Odobrenje sa portala */
	public function confDirectorAfter(Request $request)
	{
		$afterHour = AfterHour::find($request['id']);
		$employee = Employee::where('employees.id', $afterHour->employee_id)->first();
		$mail = $employee->email;
		
		$user = Sentinel::getUser(); 	// prijavljena osoba - odobrava
		$odobrio_user = Employee::where('employees.first_name', $user->first_name)->where('employees.last_name', $user->last_name)->first(); // prijavljeni djelatnik - odobrava

		$data = array(
			'odobreno'  		=> $request['odobreno'],
			'odobreno_h'  		=> $request['odobreno_h'],
			'odobrio_id'    	=> $odobrio_user->id,
			'razlog'  			=> $request['razlog'],
			'datum_odobrenja'	=> date("Y-m-d")
		);
		
		$afterHour->updateAfterHour($data);
		
		if($request['odobreno'] == 'DA'){
			$odobrenje = 'je potvrđen';
		} else {
			$odobrenje = 'nije potvrđen';
		}

	/* 	try {
			Mail::queue(
				'email.zahtjevAfterHourOD',
				['employee' => $employee,'afterHour' => $afterHour,'mail' => $mail, 'odobrenje' => $odobrenje, 'razlog'=> $afterHour->razlog ],
				function ($message) use ($mail, $employee) {
					$message->to($mail)
						->from('info@duplico.hr', 'Duplico')
						->subject('Obrađen zahtjev');
				}
			);
		} catch (Exception $e) {
			$message = session()->flash('error', 'Odobrenje je snimljeno ali mail nije poslan, došlo je do problema.');
		
			return redirect()->back()->withFlashMessage($message);
		}  */

		return redirect()->back();	
	}

	public function paidHours (Request $request) 
	{

		foreach ($request['id'] as $key => $id) {
			$afterHour = AfterHour::find($id);
			if( isset($request['paid'][$key] )) {
				$paid = $request['paid'][$key];
			} else {
				$paid = 0;
			}
			$data = array(
				'paid'  => $paid,
			);
			$afterHour->updateAfterHour($data);
		}

		$message = session()->flash('success', 'Podaci su spremljeni');
		
		return redirect()->back()->withFlashMessage($message);
	}

	public function months_afterHour()
    {
        $months = array();
		$afterHours = AfterHour::whereYear('datum',date('Y'))->get();
		$afterHours =  $afterHours->merge(AfterHour::whereYear('datum',date('Y')-1)->whereMonth('datum','>',6)->get());
        foreach ($afterHours as $key => $afterHour) {
           array_push($months, date('Y-m',strtotime($afterHour->datum )));
		}
		if( ! in_array(date('Y-m'), $months)) {
			array_push($months, date('Y-m') );
		}
		$months = array_unique($months);
		rsort($months);
		
        return $months;
    }
}