<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AfterHour;
use App\Models\Employee;
use App\Models\Registration;
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
			$afterHours = AfterHour::join('employees','after_hours.employee_id','employees.id')->select('after_hours.*','employees.first_name', 'employees.last_name')->orderBy('employees.last_name','ASC')->orderBy('datum','DESC')->get();
		} else {
			$afterHours = AfterHour::where('employee_id',$employee->id)->where('odobreno','')->orderBy('datum','DESC')->orderBy('employees.last_name','ASC')->get();				
		}
		return view('admin.afterHours.index',['afterHours'=>$afterHours, 'registration'=>$registration])->with('employee', $employee)->with('slobodni_dani', $slobodni_dani)->with('koristeni_slobodni_dani', $koristeni_slobodni_dani);
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
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
	
		return view('admin.afterHours.create',['employee'=> $employee, 'registrations'=> $registrations]);
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
					'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
					'vrijeme_od'  		=> $input['vrijeme_od'],
					'vrijeme_do'  		=> $input['vrijeme_do'],
					'napomena'  		=> $input['napomena']
				);
				
				$afterHour = new AfterHour();
				$afterHour->saveAfterHour($data);

				$employee = Employee::where('employees.id', $afterHour->employee_id)->first();
				$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $afterHour->employee_id)->select('registrations.*','works.*')->first();
				
				$nadredjeni = Employee::where('employees.id', $registration->user_id)->value('email'); // nadređeni iz uprave
				$nadredjeni1 = $registration->user_id; // id nadređene osobe
				$drugi_user_id = $registration->drugi_userId;   //postavljeno za prekovremene za IT 
				
				$emails = array('jelena.juras@duplico.hr', 'uprava@duplico.hr');
		
				$email ='uprava@duplico.hr';
	
				if($drugi_user_id){
					$nadređeni_mail = Employee::where('id', $drugi_user_id)->first()->email;
					array_push($emails, $nadređeni_mail);
				}
				
				$vrijeme = 'od ' . $input['vrijeme_od'] . ' do ' . $input['vrijeme_do']; 
				$time1 = new DateTime($input['vrijeme_od'] );
				$time2 = new DateTime($input['vrijeme_do']);
				
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
					
				} catch (Exception $e) {
					$message = session()->flash('error', 'Mail nije poslan, došlo je do problema.');
				
					return redirect()->back()->withFlashMessage($message);
				} 
			}
		} else {
			$data = array(
				'employee_id'  		=> $input['employee_id'],
				'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
				'vrijeme_od'  		=> $input['vrijeme_od'],
				'vrijeme_do'  		=> $input['vrijeme_do'],
				'napomena'  		=> $input['napomena']
			);
			
			$request_exist = GodisnjiController::afterHour_for_request($input['employee_id'], $input['datum'], $input['vrijeme_od'], $input['vrijeme_do'] );
			 if(! Sentinel::inRole('administrator') && $request_exist == false) {
				$message = session()->flash('error', 'Zahtjev za taj dan već postoji, nije moguće poslati zahtjev');    /*  AKO ZAHTJEV VEĆ POSTOJI VRATI PORUKU  */
				return redirect()->back()->withFlashMessage($message);
			} else { 
				$afterHour = new AfterHour();
				$afterHour->saveAfterHour($data);
			
				$employee = Employee::where('employees.id', $input['employee_id'])->first();
				$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $input['employee_id'])->select('registrations.*','works.*')->first();
				
				$nadredjeni = Employee::where('employees.id',$registration->user_id)->value('email'); // nadređeni iz uprave
				$nadredjeni1 = $registration->user_id; // id nadređene osobe
				$drugi_user_id = $registration->drugi_userId;   //postavljeno za prekovremene za IT 
				
				$emails = array('jelena.juras@duplico.hr', 'uprava@duplico.hr');
		
				$email ='uprava@duplico.hr';
				
				if($drugi_user_id){
					$nadređeni_mail = Employee::where('id', $drugi_user_id)->first()->email;
					array_push($emails, $nadređeni_mail);
				}
				
				$vrijeme = 'od ' . $input['vrijeme_od'] . ' do ' . $input['vrijeme_do']; 
				$time1 = new DateTime($input['vrijeme_od'] );
				$time2 = new DateTime($input['vrijeme_do']);
				
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
					
				} catch (Exception $e) {
					$message = session()->flash('error', 'Mail nije poslan, došlo je do problema.');
				
					return redirect()->back()->withFlashMessage($message);
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
		
		return view('admin.afterHours.edit', ['afterHour' => $afterHour])->with('employee', $employee);

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
			'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
			'vrijeme_od'  		=> $input['vrijeme_od'],
			'vrijeme_do'  		=> $input['vrijeme_do'],
			'napomena'  		=> $input['napomena']
		);
		$afterHour->updateAfterHour($data);
		
		$employee = Employee::where('employees.id', $input['employee_id'])->first();
		$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $input['employee_id'])->select('registrations.*','works.*')->first();
		
		$nadredjeni = Employee::where('employees.id',$registration->user_id)->value('email'); // nadređeni iz uprave
		$nadredjeni1 = $registration->user_id; // id nadređene osobe
		$drugi_user_id = $registration->drugi_userId;   //postavljeno za prekovremene za IT 
		
		$emails = array('jelena.juras@duplico.hr', 'uprava@duplico.hr');

		$email ='uprava@duplico.hr';
		
		if($drugi_user_id){
			$nadređeni_mail = Employee::where('id', $drugi_user_id)->first()->email;
			array_push($emails, $nadređeni_mail);
		}
		
		$vrijeme = 'od ' . $input['vrijeme_od'] . ' do ' . $input['vrijeme_do']; 
		$time1 = new DateTime($input['vrijeme_od'] );
		$time2 = new DateTime($input['vrijeme_do']);
		
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

		$time1 = new DateTime($afterHour->vrijeme_od );
		$time2 = new DateTime($afterHour->vrijeme_do );
		
		$interval = $time2->diff($time1);
		$interval = $interval->format('%H:%I');

		return view('admin.confirmationAfterHour')->with('afterHour', $afterHour)->with('nadredjeni1', $nadredjeni1->id)->with('interval', $interval);
	}

	public function confDirectorAfter(Request $request)
	{
		$afterHour = AfterHour::find($request['id']);
		$employee = Employee::where('employees.id', $afterHour->employee_id)->first();
		$mail = $employee->email;
		
		$user = Sentinel::getUser(); 	// prijavljena osoba - odobrava
		$odobrio_user = Employee::where('employees.first_name', $user->first_name)->where('employees.last_name', $user->last_name)->first(); // prijavljeni djelatnik - odobrava
		
		$time1 = new DateTime($afterHour->vrijeme_od );
		$time2 = new DateTime($afterHour->vrijeme_do );
		
		$interval = $time2->diff($time1);
		$interval = $interval->format('%H:%I');

		$data = array(
			'odobreno'  		=> $request['odobreno'],
			'odobreno_h'  		=> $interval,
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
		// return "Zahtjev " . $odobrenje;
		return redirect()->back();	
	}
}