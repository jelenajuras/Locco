<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\Controller;
use Sentinel;
use Session;
use PDF;
use App\Models\Equipment;
use App\Models\Users;
use App\Models\JobInterview;
use Mail;

class EmployeeController extends Controller
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
		$employees = Employee::orderBy('last_name','ASC')->get();

		return view('admin.employees.index',['employees'=>$employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $job_interviews = JobInterview::get();
		
		return view('admin.employees.create',['job_interviews'=>$job_interviews]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        //$user_id = Sentinel::getUser()->id;
		$input = $request->except(['_token']);

		$data = array(
			'first_name'  			=> $input['first_name'],
			'last_name'     		=> $input['last_name'],
			'ime_oca'     			=> $input['ime_oca'],
			'ime_majke'     		=> $input['ime_majke'],
			'oib'           		=> $input['oib'],
			'oi'           			=> $input['oi'],
			'oi_istek'           	=> date("Y-m-d", strtotime($input['oi_istek'])),
			'datum_rodjenja'		=> date("Y-m-d", strtotime($input['datum_rodjenja'])),
			'mjesto_rodjenja'       => $input['mjesto_rodjenja'],
			'mobitel'  				=> $input['mobitel'],
			'priv_mobitel'  		=> $input['priv_mobitel'],
			'email'  				=> $input['email'],
			'priv_email'  			=> $input['priv_email'],
			'prebivaliste_adresa'   => $input['prebivaliste_adresa'],
			'prebivaliste_grad'     => $input['prebivaliste_grad'],
			'boraviste_adresa'      => $input['boraviste_adresa'],
			'boraviste_grad'        => $input['boraviste_grad'],
			'zvanje'  			    => $input['zvanje'],
			'sprema'  			    => $input['sprema'],
			'konf_velicina'         => $input['konf_velicina'],
			'broj_cipela'         	=> $input['broj_cipela'],
			'bracno_stanje'  	    => $input['bracno_stanje'],
			'radnoMjesto_id'  	    => $input['radnoMjesto_id'],
			'lijecn_pregled' 	    => date("Y-m-d", strtotime($input['lijecn_pregled'])),
			'ZNR' 	   			    => date("Y-m-d", strtotime($input['ZNR'])),
			'napomena' 	   		    => $input['napomena']
		);
		
		$employee = new Employee();
		$employee->saveEmployee($data);
		
		$djelatnik = Employee::join('works','employees.radnoMjesto_id', '=', 'works.id')->select('employees.*','works.odjel','works.naziv')->where('employees.id',$employee->id)->first();
		$radno_mj=$djelatnik->naziv;
		
		//dd($radno_mj);
		
		//	$zaduzene_osobe = Equipment::distinct()->get(['User_id']);
		$email_proba = 'jelena.juras@duplico.hr'; 
		
		//foreach($zaduzene_osobe as $zaduzena_osoba){
		//	$user_mail = Users::select('id','email')->where('id',$zaduzena_osoba->User_id)->value('email');
		
		$nabava = 'marica.posaric@duplico.hr';
		 Mail::queue(
			'email.prijava',
			['djelatnik' => $djelatnik,'nabava' => $nabava,'napomena' => $input['napomena'], 'radno_mj' => $radno_mj ],
			function ($message) use ($nabava) {
				$message->to($nabava)
					->subject('Novi djelatnik - prijava');
			}
		);
		
		$administracija = 'andrea.glivarec@duplico.hr';
		Mail::queue(
			'email.prijava1',
			['djelatnik' => $djelatnik,'administracija' => $administracija,'napomena' => $input['napomena'], 'radno_mj' => $radno_mj ],
			function ($message) use ($administracija) {
				$message->to($administracija)
					->subject('Novi djelatnik - prijava');
			}
		);
		
		$zaduzene_osobe = array('petrapaola.bockor@duplico.hr','jelena.juras@duplico.hr','tomislav.novosel@duplico.hr','uprava@duplico.hr','matija.barberic@duplico.hr','nikolina.dujic@duplico.hr','marina.sindik@duplico.hr');
		
		foreach($zaduzene_osobe as $key => $zaduzena_osoba){
			Mail::queue(
			'email.prijava2',
			['djelatnik' => $djelatnik,'zaduzena_osoba' => $zaduzena_osoba,'napomena' => $input['napomena'], 'radno_mj' => $radno_mj ],
			function ($message) use ($zaduzena_osoba) {
				$message->to($zaduzena_osoba)
					->subject('Novi djelatnik - prijava');
			}
			);
		}	

		$message = session()->flash('success', 'Novi kandidat je snimljen');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.employees.index')->withFlashMessage($message);
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

		return view('admin.employees.show', ['employee' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
		
		return view('admin.employees.edit', ['employee' => $employee]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
        $employee = Employee::find($id);
		$input = $request->except(['_token']);

		$data = array(
			'first_name'  		   => $input['first_name'],
			'last_name'   		   => $input['last_name'],
			'ime_oca'     		   => $input['ime_oca'],
			'ime_majke'   		   => $input['ime_majke'],
			'oib'  		           => $input['oib'],
			'oi'          		   => $input['oi'],
			'oi_istek'    		   => date("Y-m-d", strtotime($input['oi_istek'])),
			'datum_rodjenja'  	   => date("Y-m-d", strtotime($input['datum_rodjenja'])),
			'mjesto_rodjenja' 	   => $input['mjesto_rodjenja'],
			'mobitel'  				=> $input['mobitel'],
			'priv_mobitel'  		=> $input['priv_mobitel'],
			'email'  				=> $input['email'],
			'priv_email'  			=> $input['priv_email'],
			'prebivaliste_adresa'  => $input['prebivaliste_adresa'],
			'prebivaliste_grad'    => $input['prebivaliste_grad'],
			'boraviste_adresa'     => $input['boraviste_adresa'],
			'boraviste_grad'  	   => $input['boraviste_grad'],
			'zvanje'			   => $input['zvanje'],
			'sprema'			   => $input['sprema'],
			'konf_velicina'        => $input['konf_velicina'],
			'broj_cipela'          => $input['broj_cipela'],
			'bracno_stanje'  	   => $input['bracno_stanje'],
			'radnoMjesto_id'  	   => $input['radnoMjesto_id'],
			'lijecn_pregled' 	   => date("Y-m-d", strtotime($input['lijecn_pregled'])),
			'ZNR' 	   			   => date("Y-m-d", strtotime($input['ZNR'])),
			'napomena' 	   		   => $input['napomena']
		);
		
		$employee->updateEmployee($data);
		
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.employees.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
		$employee->delete();
		
		$message = session()->flash('success', 'Kandidat je obrisan.');
		
		return redirect()->route('admin.employees.index')->withFlashMessage($message);
    }
	
	public function generate_pdf($id)
	{
	$employee = Employee::find($id);
	$pdf = PDF::loadView('documents.prijava.show', compact('employee'));
	return $pdf->download($employee->last_name . '_Prijava_dokumentacija.pdf');
	}
	
	public function lijecnicki_pdf($id)
	{
	$employee1 = Employee::find($id);
	$employee = Employee::join('works','employees.radnoMjesto_id', '=', 'works.id')->select('employees.*','works.odjel','works.naziv','works.tocke')->where('employees.id',$employee1->id)->first();

	/*$pdf = PDF::loadView('documents.lijecnicki.show', compact('employee'));
	return $pdf->download($employee->last_name . '_Uputnica.pdf');*/
	if($employee->tocke == '10, 16, 17, 18'){
		$pdf = PDF::loadView('documents.lijecnicki.show1', compact('employee'));
		return $pdf->download($employee->last_name . '_Uputnica.pdf');
	} elseif ($employee->tocke == '10'){
		$pdf = PDF::loadView('documents.lijecnicki.show2', compact('employee'));
		return $pdf->download($employee->last_name . '_Uputnica.pdf');
	} elseif (!$employee->tocke){
		$pdf = PDF::loadView('documents.lijecnicki.show3', compact('employee'));
		return $pdf->download($employee->last_name . '_Uputnica.pdf');
	}
	}
	
	public function lijecnicki($id)
	{
	$employee1 = Employee::find($id);
	$employee = Employee::join('works','employees.radnoMjesto_id', '=', 'works.id')->select('employees.*','works.odjel','works.naziv','works.tocke')->where('employees.id',$employee1->id)->first();

	/*$pdf = PDF::loadView('documents.lijecnicki.show', compact('employee'));
	return $pdf->download($employee->last_name . '_Uputnica.pdf');*/
	if($employee->tocke == '10, 16, 17, 18'){
		return view('documents.lijecnicki.show1', ['employee' => $employee]);
	} elseif ($employee->tocke == '10'){
		return view('documents.lijecnicki.show2', ['employee' => $employee]);
	} elseif (!$employee->tocke){
		return view('documents.lijecnicki.show3', ['employee' => $employee]);
	}
	}
	
	public function prijava_pdf($id)
	{
	$employee = Employee::find($id);
	
	$pdf = PDF::loadView('documents.prijava.show1', compact('employee'));
	return $pdf->download($employee->last_name . '_Prijava.pdf');
	
	}
}
