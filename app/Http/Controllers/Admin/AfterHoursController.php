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
		$koristeni_slobodni_dani =  $this->koristeni_slobodni_dani($registration);/* računa iskorištene slobodne dane */

		if(Sentinel::inRole('administrator')){
			$afterHours = AfterHour::join('employees','after_hours.employee_id','employees.id')->select('after_hours.*','employees.first_name', 'employees.last_name')->orderBy('datum','DESC')->get();
		} else {
			$afterHours = AfterHour::where('employee_id',$employee->id)->where('odobreno','')->orderBy('datum','DESC')->get();				
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
		
		return view('admin.afterHours.create')->with('employee', $employee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AfterHourRequest $request)
    {
        $input = $request->except(['_token']);
		//dd($input);
		$data = array(
			'employee_id'  		=> $input['employee_id'],
			'datum'    			=> date("Y-m-d", strtotime($input['datum'])),
			'vrijeme_od'  		=> $input['vrijeme_od'],
			'vrijeme_do'  		=> $input['vrijeme_do'],
			'napomena'  		=> $input['napomena']
		);
		
		$afterHour = new AfterHour();
		$afterHour->saveAfterHour($data);
	
		$employee = Employee::where('employees.id',$input['employee_id'])->first();
		$registration = Registration::join('works','registrations.radnoMjesto_id','works.id')->where('registrations.employee_id', $input['employee_id'])->select('registrations.*','works.*')->first();
		
		$nadredjeni = Employee::where('employees.id',$registration->user_id)->value('email');
		
		$proba = array('jelena.juras@duplico.hr');
		$uprava = array('uprava@duplico.hr');
		
		$nadredjeni1 = $registration->user_id; // id nadređene osobe
		
		$vrijeme = 'od ' . $input['vrijeme_od'] . ' do ' . $input['vrijeme_do']; 
		
		//if($nadredjeni){
			Mail::queue(
				'email.zahtjevAfterHour',
				['employee' => $employee,'datum' => $input['datum'],'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'napomena' => $input['napomena'],'vrijeme' => $vrijeme ],
				function ($message) use ($uprava, $employee) {
					$message->to($uprava)
						->from('info@duplico.hr', 'Duplico')
						->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
			Mail::queue(
				'email.zahtjevAfterHour',
				['employee' => $employee,'datum' => $input['datum'],'afterHour' => $afterHour,'nadredjeni1' => $nadredjeni1,'napomena' => $input['napomena'],'vrijeme' => $vrijeme ],
				function ($message) use ($proba, $employee) {
					$message->to($proba)
						->from('info@duplico.hr', 'Duplico')
						->subject('Zahtjev - ' .  $employee->first_name . ' ' .  $employee->last_name);
				}
			);
		//}
			
		$message = session()->flash('success', 'Zahtjev je poslan');
			
		//return redirect()->back()->withFlashMessage($message);
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
		
		return redirect()->route('home')->withFlashMessage($message);
    }
	
	public function storeConf(Request $request)
    {
		$input = $request->except(['_token']);
		$afterHour = AfterHour::find($_GET['id']);

		$employee_id = $afterHour->employee_id;
		$employee = Employee::where('employees.id', $employee_id)->first();
		$mail = $employee->email;
		
		$data = array(
			'odobreno'  		=>  $_GET['odobreno'],
			'odobrio_id'    	=> $_GET['user_id'],
			'razlog'  		=>  $_GET['razlog'],
			'datum_odobrenja'	=> date("Y-m-d", strtotime($_GET['datum_odobrenja']))
		);
		
		$afterHour->updateAfterHour($data);
		
		if($input['odobreno'] == 'DA'){
			$odobrenje = 'je potvrđen';
		} else {
			$odobrenje = 'nije potvrđen';
		}
		
		Mail::queue(
			'email.zahtjevAfterHourOD',
			['employee' => $employee,'afterHour' => $afterHour,'mail' => $mail, 'odobrenje' => $odobrenje, 'razlog'=> $_GET['razlog']],
			function ($message) use ($mail, $employee) {
				$message->to($mail)
					->from('info@duplico.hr', 'Duplico')
					->subject('Odobrenje zahtjeva');
			}
		);
		
		$message = session()->flash('success', 'Zahtjev je potvrđen');
		
		return redirect()->route('home')->withFlashMessage('Zahtjev je odobren');
    }

	public function confirmationAfter_show(Request $request)
	{
		$user = Sentinel::getUser();
		$nadredjeni1 = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		return view('admin.confirmationAfterHour')->with('afterHour_id', $request->id)->with('nadredjeni1', $nadredjeni1->id);
	}
	
}
