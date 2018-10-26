<?php

namespace App\Http\Controllers\Admin;

use App\Models\Registration;
use App\Models\Employee;
use App\Models\Work;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Controllers\Controller;
use Sentinel;
use DB;
use Session;
use PDF;
use DateTime;
use Mail;

class RegistrationController extends Controller
{
	/**
   * Set middleware to quard controller.
   *
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
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('employees.last_name','ASC')->get();
		
		//dd($registrations);
		return view('admin.registrations.index',['registrations'=>$registrations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$employee = Employee::where('id', $request->id)->first();
		
		return view('admin.registrations.create',['employee' => $employee]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegistrationRequest $request)
    {
        $input = $request->except(['_token']);
		
		if($input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'] == '--'){
			$staz = '0-0-0';
		}else {
			$staz = $input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'];
		}
	
		$data = array(
			'employee_id'  		=> $input['employee_id'],
			'radnoMjesto_id'    => $input['radnoMjesto_id'],
			'datum_prijave'		=> date("Y-m-d", strtotime($input['datum_prijave'])),
			'probni_rok'  		=> $input['probni_rok'],
			'staz'   			=> $staz,
			'lijecn_pregled'    => date("Y-m-d", strtotime($input['lijecn_pregled'])),
			'ZNR'      			=> date("Y-m-d", strtotime($input['ZNR'])),
			'napomena'  	    => $input['napomena']
		);
		
		
		$registration = new Registration();
		$registration->saveRegistration($data);

		$employee = $input['employee_id'];
		$djelatnik = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->join('works','registrations.radnoMjesto_id', '=', 'works.id')->select('registrations.*','employees.first_name','employees.last_name','works.odjel','works.naziv')->where('registrations.employee_id', $employee)->first();
		
		$radno_mj = $djelatnik->naziv;
		$ime = $djelatnik->first_name;
		$prezime = $djelatnik->last_name;
		$work = Work::leftjoin('users','users.id','works.user_id')->where('works.id',$djelatnik->radnoMjesto_id)->first();
		
		$zaduzene_osobe = array('andrea.glivarec@duplico.hr','marica.posaric@duplico.hr','jelena.juras@duplico.hr','uprava@duplico.hr','matija.barberic@duplico.hr');
		
		//$zaduzene_osobe = array('jelena.juras@duplico.hr','jelena.juras@duplico.hr');
		
		foreach($zaduzene_osobe as $key => $zaduzena_osoba){
			Mail::queue(
			'email.prijava3',
			['djelatnik' => $djelatnik,'zaduzena_osoba' => $zaduzena_osoba,'napomena' => $input['napomena'], 'radno_mj' => $radno_mj, 'ime' => $ime, 'prezime' => $prezime ],
			function ($message) use ($zaduzena_osoba) {
				$message->to($zaduzena_osoba)
					->subject('Novi djelatnik - obavijest o' . ' početku ' . ' rada');
			}
			);
		}	
		
		$zaduzen = ('tomislav.novosel@duplico.hr');
		$ime1 = $work->first_name;
		$prezime1 = $work->last_name;
		
		Mail::queue(
		'email.prijava4',
		['djelatnik' => $djelatnik,'zaduzen' => $zaduzen,'napomena' => $input['napomena'], 'radno_mj' => $radno_mj, 'ime' => $ime, 'prezime' => $prezime,'ime1' => $ime1, 'prezime1' => $prezime1,],
		function ($message) use ($zaduzen) {
			$message->to($zaduzen)
				->subject('Novi djelatnik - prijava');
		}
		);
		
		// Create directory
		$path = 'storage/' . $prezime . '_' . $ime;
		mkdir($path);
	
		$message = session()->flash('success', 'Novi djelatnik je prijavljen');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.registrations.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$registration = Registration::find($id);
		
		return view('admin.registrations.show', ['registration' => $registration]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {	
        $registration = Registration::find($id);
		$stažY = 0;
		$stažM = 0;
		$stažD = 0;
		if($registration->staz) {
			$staž = $registration->staz;
		$staž = explode('-',$registration->staz);
		$stažY = $staž[0];
		$stažM = $staž[1];
		$stažD = $staž[2];
		}
		
		
		return view('admin.registrations.edit', ['registration' => $registration])->with('stažY', $stažY)->with('stažM', $stažM)->with('stažD', $stažD);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RegistrationRequest $request, $id)
    {
        $registration = Registration::find($id);
		$input = $request->except(['_token']);
		
		//if(!$input['datum_odjave']){
			$data = array(
				'employee_id'  		=> $input['employee_id'],
				'radnoMjesto_id'    => $input['radnoMjesto_id'],
				'datum_prijave'		=> date("Y-m-d", strtotime($input['datum_prijave'])),
				'probni_rok'  		=> $input['probni_rok'],
				'staz'   			=> $input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'],
				'lijecn_pregled'    => date("Y-m-d", strtotime($input['lijecn_pregled'])),
				'ZNR'      			=> date("Y-m-d", strtotime($input['ZNR'])),
				'napomena'  	    => $input['napomena']
			);
		/*} else {
		$data = array(
			'employee_id'  		=> $input['employee_id'],
			'radnoMjesto_id'    => $input['radnoMjesto_id'],
			'datum_prijave'		=> date("Y-m-d", strtotime($input['datum_prijave'])),
			'probni_rok'  		=> $input['probni_rok'],
			'staz'   			=> $input['staz'],
			'lijecn_pregled'    => date("Y-m-d", strtotime($input['lijecn_pregled'])),
			'ZNR'      			=> date("Y-m-d", strtotime($input['ZNR'])),
			'napomena'  	    => $input['napomena'],
			'datum_odjave'		=> date("Y-m-d", strtotime($input['datum_odjave'])),
			'otkaz_id'   		=> $input['otkaz_id'],
			'otkazni_rok'   	=> $input['otkazni_rok']
	
		);
		}*/
		
		$registration->updateRegistration($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.registrations.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registration = Registration::find($id);
		$registration->delete();
		
		$message = session()->flash('success', 'Kandidat je obrisan.');
		
		return redirect()->route('admin.registrations.index')->withFlashMessage($message);
    }
	
	public function generate_pdf($id) 
	{
	$registration = Registration::find($id);
	$pdf = PDF::loadView('admin.registrations.show', compact('registration'));
	return $pdf->download('djelatnik_'. $registration->id .'.pdf');
	}
}
