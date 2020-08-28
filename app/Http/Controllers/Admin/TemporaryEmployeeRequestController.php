<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\TemporaryEmployeeRequest;
use App\Models\TemporaryEmployee;
use App\Models\Employee;
use App\Models\Department;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GodisnjiController;
use Mail;
use Sentinel;
use DateTime;

class TemporaryEmployeeRequestController extends Controller
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
    public function index(Request $request)
    {
        $temporaryEmployee = TemporaryEmployee::find($request ['id']);
        $requests = TemporaryEmployeeRequest::where('employee_id', $temporaryEmployee->id )->get();
        
        return view('admin.temporary_employee_requests.index',['temporaryEmployee'=>$temporaryEmployee, 'requests'=>$requests]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Sentinel::getUser();
        $temporaryEmployee = TemporaryEmployee::where('first_name',  $user->first_name)->where('last_name',  $user->last_name)->first();

        $temporaryEmployees = TemporaryEmployee::where('odjava', null)->get();
        return view('admin.temporary_employee_requests.create',  ['temporaryEmployees'=>$temporaryEmployees, 'temporaryEmployee'=>$temporaryEmployee] );
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

		if($input['end_date'] == '' ){
			$input['end_date'] = $input['start_date'];
		}

		if(is_array($input['employee_id']) ) {   /* ZAHTJEV NA VIŠE DJELATNIKA */
			foreach($input['employee_id'] as $employee_id){
				$data = array(
				'zahtjev'  			=> $input['zahtjev'],
				'employee_id'  		=> $employee_id,
				'start_date'    		=> date("Y-m-d", strtotime($input['start_date'])),
				'end_date'		=> date("Y-m-d", strtotime($input['end_date'])),
				'start_time'  		=> $input['start_time'],
				'end_time'  		=> $input['end_time'],
				'napomena'  		=> $input['napomena'],
				'odobreno' 			=> '',   // ODOBRENO ILI NE????
				'odobrio_id' 		=> '58'
				);
				
				$request = new TemporaryEmployeeRequest();
				$request->saveTemporaryEmployeeRequest($data);
			}
		} else {  																			/* ZAHTJEV NA JEDNOG DJELATNIKA*/
			$employee = TemporaryEmployee::where('id', $input['employee_id'] )->first(); 	/* djelatnik */
			$employee_mail = $employee->email;										/* mail djelatnika */
			
            $data = array(
                'zahtjev'  			=> $input['zahtjev'],
                'employee_id'  		=> $employee->id,
                'start_date'    		=> date("Y-m-d", strtotime($input['start_date'])),
                'end_date'		=> date("Y-m-d", strtotime($input['end_date'])),
                'start_time'  		=> $input['start_time'],
                'end_time'  		=> $input['end_time'],
                'napomena'  		=> $input['napomena']
            );
            if($input['zahtjev'] == 'Bolovanje'){
                $data += ['odobreno' => 'DA'];
            }

            $request = new TemporaryEmployeeRequest();
            $request->saveTemporaryEmployeeRequest($data);

            if($input['zahtjev'] == 'Izlazak') {
                $zahtjev2 = 'prijevremeni izlaz';
                $vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
            } elseif($input['zahtjev'] == 'Bolovanje'){
                $zahtjev2 = 'bolovanje';
                $vrijeme="";
            } elseif($input['zahtjev'] == 'SLD'){
                $zahtjev2 = 'slobodan dan';
                $vrijeme="";
            } elseif($input['zahtjev'] == 'VIK'){
                $zahtjev2 = 'slobodan vikend';
                $vrijeme="";
            }
				
            if( $input['email'] == 'DA' ) {
              
                $work = $employee->work;   //radno mjesto zaposlenika
                // $department = $work->department; 							// odjel kojem pripada radno mjesto
                // $department_nadredjeni = $department->employee; 			 // nadređeni odjela
                $work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta
                $work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
                $registration_superior = $employee->superior;   				// nadređeni djelatnik - Registration
                /*
                if($registration_superior) {
                    $mail_work_voditelj = $registration_superior->email;
                } */
                
                if($work_voditelj) {
                    $mail_work_voditelj = $work_voditelj->email;
                } elseif($work_nadredjeni) {
                    $mail_work_voditelj = $work_nadredjeni->email;	
                }

                $zahtjev = array('start_date' =>$input['start_date'], 'end_date' =>$input['end_date']);  // array zatjev početak - kraj
                $dani_zahtjev = GodisnjiController::daniGO($zahtjev); 		
                
                $ja = 'jelena.juras@duplico.hr';
               
                try {
                   
                    if(isset($mail_work_voditelj)){
                        if($input['zahtjev'] == 'Bolovanje'){ 			// ako je bolovanje
                            Mail::queue(
                                'email.zahtjevTemp',
                                ['employee' => $employee,'request' => $request,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date']],
                                function ($message) use ($ja, $employee) {
                                    $message->to('matija.barberic@duplico.hr')
                                            ->from('info@duplico.hr', 'Duplico')
                                            ->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
                                }
                            );
                            Mail::queue(
                                'email.zahtjevTemp',
                                ['employee' => $employee,'request' => $request,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date']],
                                function ($message) use ($mail_work_voditelj, $employee) {
                                    $message->to($mail_work_voditelj)
                                            ->from('info@duplico.hr', 'Duplico')
                                            ->subject('Prijavljeno bolovanje - ' .  $employee->first_name . ' ' .  $employee->last_name);
                                }
                            );
                        } else {										// svi ostali zahtjevi
                            if($mail_work_voditelj != '' || $mail_work_voditelj != null) {
                                Mail::queue(
                                    'email.zahtjevTemp',
                                    ['employee' => $employee,'request' => $request,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date']],
                                    function ($message) use ($mail_work_voditelj, $employee) {
                                        $message->to($mail_work_voditelj)
                                            ->from('info@duplico.hr', 'Duplico')
                                            ->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
                                    }
                                );
                                Mail::queue(
                                    'email.zahtjevTemp',
                                    ['employee' => $employee,'request' => $request,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date']],
                                    function ($message) use ($ja, $employee) {
                                        $message->to($ja)
                                            ->from('info@duplico.hr', 'Duplico')
                                            ->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
                                    }
                                );
                                Mail::queue(
                                    'email.zahtjevTemp',
                                    ['employee' => $employee,'request' => $request,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date']],
                                    function ($message) use ($ja, $employee) {
                                        $message->to('matija.barberic@duplico.hr')
                                            ->from('info@duplico.hr', 'Duplico')
                                            ->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
                                    }
                                );
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    $message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
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
        $request = TemporaryEmployeeRequest::find($id);
        $temporaryEmployees = TemporaryEmployee::where('odjava', null)->get();

        return view('admin.temporary_employee_requests.edit',  ['request'=>$request, 'temporaryEmployees'=>$temporaryEmployees] );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request = TemporaryEmployeeRequest::find($id);

        $employee = $request->employee; 	/* djelatnik */
		$employee_mail = $employee->email;										/* mail djelatnika */
			
        $data = array(
            'zahtjev'  			=> $input['zahtjev'],
            'employee_id'  		=> $employee->id,
            'start_date'    		=> date("Y-m-d", strtotime($input['start_date'])),
            'end_date'		=> date("Y-m-d", strtotime($input['end_date'])),
            'start_time'  		=> $input['start_time'],
            'end_time'  		=> $input['end_time'],
            'napomena'  		=> $input['napomena']
        );
        if($input['zahtjev'] == 'Bolovanje'){
            $data += ['odobreno' => 'DA'];
        }
      
        $request->updateTemporaryEmployeeRequest($data);

        if($input['zahtjev'] == 'Izlazak') {
            $zahtjev2 = 'prijevremeni izlaz';
            $vrijeme = 'od ' . $input['start_time'] . ' do ' . $input['end_time']; 
        } elseif($input['zahtjev'] == 'Bolovanje'){
            $zahtjev2 = 'bolovanje';
            $vrijeme="";
        } elseif($input['zahtjev'] == 'SLD'){
            $zahtjev2 = 'slobodan dan';
            $vrijeme="";
        } elseif($input['zahtjev'] == 'VIK'){
            $zahtjev2 = 'slobodan vikend';
            $vrijeme="";
        }
				
        if( $input['email'] == 'DA' ) {
            
            $work = $employee->work;   //radno mjesto zaposlenika
            // $department = $work->department; 							// odjel kojem pripada radno mjesto
            // $department_nadredjeni = $department->employee; 			 // nadređeni odjela
            $work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta
            $work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
            $registration_superior = $employee->superior;   				// nadređeni djelatnik - Registration
            /*
            if($registration_superior) {
                $mail_work_voditelj = $registration_superior->email;
            } */
            
            if($work_voditelj) {
                $mail_work_voditelj = $work_voditelj->email;
            } elseif($work_nadredjeni) {
                $mail_work_voditelj = $work_nadredjeni->email;	
            }

            $zahtjev = array('start_date' =>$input['start_date'], 'end_date' =>$input['end_date']);  // array zatjev početak - kraj
            $dani_zahtjev = GodisnjiController::daniGO($zahtjev); 		
            
            $ja = 'jelena.juras@duplico.hr';
            
            try {
               
                 if($mail_work_voditelj != '' || $mail_work_voditelj != null) {
                    Mail::queue(
                        'email.zahtjevTemp',
                        ['employee' => $employee,'request' => $request,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date']],
                        function ($message) use ($mail_work_voditelj, $employee) {
                            $message->to($mail_work_voditelj)
                                ->from('info@duplico.hr', 'Duplico')
                                ->subject('Ispravek zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
                        }
                    );
                    Mail::queue(
                        'email.zahtjevTemp',
                        ['employee' => $employee,'request' => $request,'zahtjev2' => $zahtjev2,'vrijeme' => $vrijeme, 'dani_zahtjev' => $dani_zahtjev, 'end_date' => $input['end_date']],
                        function ($message) use ($ja, $employee) {
                            $message->to($ja)
                                ->from('info@duplico.hr', 'Duplico')
                                ->subject('Ispravek zahtjeva - ' .  $employee->first_name . ' ' .  $employee->last_name);
                        }
                    );
                } 
            } catch (\Throwable $th) {
                $message = session()->flash('error', 'Mail nije poslan, problem sa spajanjem na mail server');
                return redirect()->back()->withFlashMessage($message);
            }
        }		

		$message = session()->flash('success', 'Zahtjev je poslan');

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
        $request = TemporaryEmployeeRequest::find($id);

        $request->delete();
        
        $message = session()->flash('success', 'Zajhtjev je obrisan');
		
        return redirect()->back()->withFlashMessage($message);
    }

    
    public function storeConf(Request $request)
    {
		$input = $request->except(['_token']);
		$vacationRequest = TemporaryEmployeeRequest::find($input['id']);
		
		if(! $vacationRequest) {
			$message = session()->flash('error', 'Nešto je pošlo krivo, zahtjev nije nađen u bazi, javite se administratoru portala');
		
			return redirect()->route('home')->withFlashMessage($message);
		}
		$user = Sentinel::getUser(); 																	// prijavljena osoba - odobrava
		$odobrio_user = Employee::where('employees.first_name', $user->first_name)->where('employees.last_name', $user->last_name)->first(); // prijavljeni djelatnik - odobrava
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name ;					// ime prijavljenog djelatnika koji odobrava

		$employee = TemporaryEmployee::where('id', $vacationRequest->employee_id)->first();    // djelatnik koji je poslao zahtjev 			
		$ime = $employee->first_name . ' ' . $employee->last_name;                          // ime djelatnika koji je poslao zahtjev 
		$employee_mail = $employee->email;										// mail djelatnika koji je poslao zahtjev 
					
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

		$vacationRequest->updateTemporaryEmployeeRequest($data);
		
		if($input['email'] == 'DA' ){ 
			$subject = 'Odobrenje zahtjeva ';

			if( ( $vacationRequest->odobreno == 'DA' ) ) {
				$odobrenje = 'je odobren';
			} 
			if( ( $vacationRequest->odobreno == 'NE' ) ) {
				$odobrenje = 'nije odobren';
				$subject = 'Odbijen zahtjev ';
			}
			
			if ($vacationRequest->zahtjev == 'Bolovanje') {
				$zahtjev2 = 'bolovanje';
			} elseif ($vacationRequest->zahtjev == 'Izlazak'){
				$zahtjev2 = 'izlazak';
			} elseif ($vacationRequest->zahtjev == 'SLD'){
				$zahtjev2 = 'slobodan dan';
			} elseif ($vacationRequest->zahtjev == 'VIK'){
				$zahtjev2 = 'slobodan vikend';
			}

			if($vacationRequest->napomena) {
				$zahtjev2 .= ' (' . $vacationRequest->napomena . ')';
			}
			
			$work =  $employee->work;   //radno mjesto zaposlenika
            $department = $work->department; 							// odjel kojem pripada radno mjesto
            if( ! $department) {
                $department = Department::where('name',$work->odjel)->first();
            }
            $department_nadredjeni = $department->employee; 			 // nadređeni odjela
			$work_nadredjeni = $work->nadredjeni;    					// glavni nadređeni radnog mjesta - član uprave
			$work_voditelj = $work->prvi_nadredjeni;    				// voditelj radnog mjesta
			$email_work_nadredjeni = $work_nadredjeni->email;				// email glavnog nadređenog
			$superior_mail = $employee->superior['email'];   //mail nadređenog djelatnika

            $mail_djelatnik = $employee_mail;

			$uprava = array('zeljko.rendulic@duplico.hr','durdica.rendulic@duplico.hr','ivan.rendulic@duplico.hr','nikola.rendulic@duplico.hr','matija.rendulic@duplico.hr', $mail_djelatnik);
			$mail_to = array($email_work_nadredjeni, $superior_mail,'pravni@duplico.hr','jelena.juras@duplico.hr');

			$mails = array_diff( array_unique(array_merge($uprava, $mail_to)), array( $odobrio_user->email )); // svi mailovi uprava, djelatnik i voditelj - bez duplih, bez onog tko je odobrio
			    try {
                    foreach($mails as $mail) {  
                        if($mail != '' && $mail != null) {
                            Mail::queue(
                                'email.zahtjevOD_uprave_temp',    // mail sa svim podacima
                                ['employee' => $employee,'vacationRequest' => $vacationRequest,'employee_mail' => $employee_mail, 'odobrenje' => $odobrenje, 'zahtjev2' => $zahtjev2, 'razlog'=> $request['razlog'], 'odobrio' => $odobrio, 'ime' => $ime, 'subject' => $subject],
                                function ($message) use ($mail, $employee, $subject) {
                                    $message->to($mail)
                                        ->from('info@duplico.hr', 'Duplico')
                                        ->subject($subject . ' - ' .  $employee->first_name . ' ' . $employee->last_name);
                                }
                            );
                        }
                    }
                   
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
}
