<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TemporaryEmployee;
use App\Models\Employee;
use App\Models\Work;
use Mail;


class TemporaryEmployeeController extends Controller
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
        $temporaryeEmployees = TemporaryEmployee::orderBy('last_name','ASC')->get();
      
        return view('admin.temporary_employees.index', ['temporaryeEmployees'=>$temporaryeEmployees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::orderBy('last_name','ASC')->get();
        $works = Work:: orderBy('odjel','ASC')->orderBy('naziv','ASC')->get();
       
        return view('admin.temporary_employees.create', ['employees'=>$employees, 'works'=>$works]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array(
            'first_name'  	    => $request['first_name'],
            'last_name'  	    => $request['last_name'],
            'radnoMjesto_id'    => $request['radnoMjesto_id'],
            'superior_id'       => $request['superior_id'],
            'datum_prijave'     => $request['datum_prijave'],
            'napomena'          => $request['napomena'],
            'odjava'            => $request['odjava'],
			'ime_oca'     			=> $request['ime_oca'],
			'ime_majke'     		=> $request['ime_majke'],
			'oib'           		=> $request['oib'],
			'oi'           			=> $request['oi'],
			'oi_istek'           	=> date("Y-m-d", strtotime($request['oi_istek'])),
			'datum_rodjenja'		=> date("Y-m-d", strtotime($request['datum_rodjenja'])),
			'mjesto_rodjenja'       => $request['mjesto_rodjenja'],
			'mobitel'  				=> $request['mobitel'],
			'priv_mobitel'  		=> $request['priv_mobitel'],
			'email'  				=> $request['email'],
			'priv_email'  			=> $request['priv_email'],
			'prebivaliste_adresa'   => $request['prebivaliste_adresa'],
			'prebivaliste_grad'     => $request['prebivaliste_grad'],
			'zvanje'  			    => $request['zvanje'],
			'sprema'  			    => $request['sprema'],
			'konf_velicina'         => $request['konf_velicina'],
			'broj_cipela'         	=> $request['broj_cipela'],
			'bracno_stanje'  	    => $request['bracno_stanje']
		);

		$temporaryEmployee = new TemporaryEmployee();
        $temporaryEmployee->saveTemporaryEmployee($data);        

        $zaduzene_osobe = array('andrea.glivarec@duplico.hr','marica.posaric@duplico.hr','jelena.juras@duplico.hr','uprava@duplico.hr','petrapaola.bockor@duplico.hr','matija.barberic@duplico.hr','nikolina.dujic@duplico.hr','marina.sindik@duplico.hr' );
       // $zaduzene_osobe = array('jelena.juras@duplico.hr');
        foreach($zaduzene_osobe as $key => $zaduzena_osoba){
			Mail::queue(
			'email.prijava_temp',
			['djelatnik' => $temporaryEmployee,'napomena' => $request['napomena'], 'radno_mj' => $temporaryEmployee->work->naziv],
			function ($message) use ($zaduzena_osoba) {
				$message->to($zaduzena_osoba)
					->subject('Privremeni djelatnik - obavijest o' . ' početku ' . ' rada');
			}
			);
        }

        $message = session()->flash('success', 'Uspješno je spremljen novi djelatnik');
		
     //   return redirect()->route('admin.temporary_employees.index')->withFlashMessage($message);

        return redirect()->route('users.create')->with('djelatnik', $temporaryEmployee)->withFlashMessage($message); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $temporaryEmployee = TemporaryEmployee::find($id);

        return view('admin.temporary_employees.show', ['temporaryEmployee'=>$temporaryEmployee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $temporaryEmployee = TemporaryEmployee::find($id);
        $employees = Employee::orderBy('last_name','ASC')->get();
        $works = Work:: orderBy('odjel','ASC')->orderBy('naziv','ASC')->get();

        return view('admin.temporary_employees.edit', ['temporaryEmployee'=>$temporaryEmployee, 'employees'=>$employees, 'works'=>$works]);
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
        $temporaryEmployee = TemporaryEmployee::find($id);

        $data = array(
            'first_name'  	    => $request['first_name'],
            'last_name'  	    => $request['last_name'],
            'radnoMjesto_id'    => $request['radnoMjesto_id'],
            'superior_id'       => $request['superior_id'],
            'datum_prijave'     => $request['datum_prijave'],
            'napomena'          => $request['napomena'],
            'odjava'            => $request['odjava'],
			'ime_oca'     			=> $request['ime_oca'],
			'ime_majke'     		=> $request['ime_majke'],
			'oib'           		=> $request['oib'],
			'oi'           			=> $request['oi'],
			'oi_istek'           	=> date("Y-m-d", strtotime($request['oi_istek'])),
			'datum_rodjenja'		=> date("Y-m-d", strtotime($request['datum_rodjenja'])),
			'mjesto_rodjenja'       => $request['mjesto_rodjenja'],
			'mobitel'  				=> $request['mobitel'],
			'priv_mobitel'  		=> $request['priv_mobitel'],
			'email'  				=> $request['email'],
			'priv_email'  			=> $request['priv_email'],
			'prebivaliste_adresa'   => $request['prebivaliste_adresa'],
			'prebivaliste_grad'     => $request['prebivaliste_grad'],
			'zvanje'  			    => $request['zvanje'],
			'sprema'  			    => $request['sprema'],
			'konf_velicina'         => $request['konf_velicina'],
			'broj_cipela'         	=> $request['broj_cipela'],
			'bracno_stanje'  	    => $request['bracno_stanje']
		);


        $temporaryEmployee->updateTemporaryEmployee($data);    

        $message = session()->flash('success', 'Podaci su ispravljeni');
		
        return redirect()->route('admin.temporary_employees.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temporaryEmployee = TemporaryEmployee::find($id);
        $temporaryEmployee->delete();

        $message = session()->flash('success', 'Djelatnik je obrisan');
		
        return redirect()->route('admin.temporary_employees.index')->withFlashMessage($message);

    }
}
