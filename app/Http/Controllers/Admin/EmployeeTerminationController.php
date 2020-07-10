<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmployeeTermination;
use App\Models\Employee;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeTerminationRequest;
use App\Http\Controllers\Controller;
use Sentinel;
use Session;
use Mail;

class EmployeeTerminationController extends Controller
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
        $employee_terminations = EmployeeTermination::orderBy('datum_odjave','DESC')->paginate(50);

		return view('admin.employee_terminations.index',['employee_terminations'=>$employee_terminations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::orderBy('last_name','ASC')->get();
		return view('admin.employee_terminations.create')->with('employees', $employees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeTerminationRequest $request)
    {
        $input = $request->except(['_token']);
		//dd($input);
		$data = array(
			'employee_id'  	 => $input['employee_id'],
			'otkaz_id'   	 => $input['otkaz_id'],
			'otkazni_rok'	 => $input['otkazni_rok'],
			'datum_odjave'	 => date("Y-m-d", strtotime($input['datum_odjave'])),
			'napomena'     	 => $input['napomena']
		);
		$employee_terminations = new EmployeeTermination();
		$employee_terminations->saveEmployeeTermination($data);
		
		$odjava = array(
			'odjava'  => 'DA'
		);
		
		$djelatnik = Registration::where('employee_id', $input['employee_id']);
		$djelatnik->update($odjava);
		
		$employee = $input['employee_id'];
		$djelatnik = EmployeeTermination::join('employees','employee_terminations.employee_id', '=', 'employees.id')->join('registrations','employee_terminations.employee_id', '=', 'registrations.employee_id')->join('works','registrations.radnoMjesto_id', '=', 'works.id')->select('employee_terminations.*','employees.first_name','employees.last_name','works.odjel','works.naziv')->where('employee_terminations.employee_id', $employee)->first();

		$ime = $djelatnik->first_name;
		$prezime = $djelatnik->last_name;
		$radno_mj = $djelatnik->naziv;
				
		$zaduzene_osobe = array('pravni@duplico.hr','petrapaola.bockor@duplico.hr','jelena.juras@duplico.hr','uprava@duplico.hr','tomislav.novosel@duplico.hr','matija.barberic@duplico.hr', 'marica.posaric@duplico.hr','nikolina.dujic@duplico.hr','marina.sindik@duplico.hr');
		
		//$zaduzene_osobe = array('jelena.juras@duplico.hr','jelena.juras@duplico.hr');
		
		foreach($zaduzene_osobe as $key => $zaduzena_osoba){
			Mail::queue(
			'email.Odjava',
			['djelatnik' => $djelatnik,'zaduzena_osoba' => $zaduzena_osoba,'napomena' => $input['napomena'], 'radno_mj' => $radno_mj, 'ime' => $ime, 'prezime' => $prezime ],
			function ($message) use ($zaduzena_osoba, $ime, $prezime ) {
				$message->to($zaduzena_osoba)
					->subject('Odjava djelatnika ' . ' - ' . $ime . ' ' .  $prezime);
			}
			);
		}
		
		$message = session()->flash('success', 'Djelatnik je odjavljen.');
		
		return redirect()->route('admin.employee_terminations.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee_termination = EmployeeTermination::find($id);
		$registration = Registration::where('registrations.employee_id',$employee_termination->employee_id)->join('works','registrations.radnoMjesto_id', '=', 'works.id')->select('registrations.*','works.odjel','works.naziv')->first();
		
		
		return view('admin.employee_terminations.show', ['employee_termination' => $employee_termination])->with('registration', $registration);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee_termination = EmployeeTermination::find($id);
		
		return view('admin.employee_terminations.edit', ['employee_termination' => $employee_termination]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeTerminationRequest $request, $id)
    {
        $employee_termination = EmployeeTermination::find($id);
		$input = $request->except(['_token']);
		//dd($input);
			$data = array(
				'employee_id'  	 => $input['employee_id'],
				'otkaz_id'   	 => $input['otkaz_id'],
				'otkazni_rok'	 => $input['otkazni_rok'],
				'datum_odjave'	 => date("Y-m-d", strtotime($input['datum_odjave'])),
				'napomena'     	 => $input['napomena']
			);
			$employee_termination->updateEmployeeTermination($data);
			
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.employee_terminations.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee_termination = EmployeeTermination::find($id);
		$employee_termination->delete();
		
		$message = session()->flash('success', 'Odjava je obrisana.');
		
		return redirect()->route('admin.employee_terminations.index')->withFlashMessage($message);
    }
}
