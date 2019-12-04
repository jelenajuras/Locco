<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use Sentinel;

class CarController extends Controller
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
      $vozila = Car::orderBy('registracija','ASC')->get();
      
      return view('admin.cars.index',['vozila'=>$vozila]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::orderBy('last_name','ASC')->get();
		
		return view('admin.cars.create',['employees'=>$employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarRequest $request)
    {
        $input = $request;

		$data = array(
			'proizvođač'  	=> $input['proizvođač'],
			'model'  		=> $input['model'],
			'registracija'  => $input['registracija'],
			'šasija' 		=> $input['šasija'],
			'prva_registracija'  => $input['prva_registracija'],
			'zadnja_registracija'  => $input['zadnja_registracija'],
			'trenutni_kilometri'  => $input['trenutni_kilometri'],
			'zadnji_servis'  => $input['zadnji_servis'],
			//'department_id'  => $input['department_id'],
			'user_id'  => $input['user_id']
		);
		
		$vozilo = new Car();
		$vozilo->saveCar($data);
		
		$message = session()->flash('success', 'Uspješno je dodano novo vozilo');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.cars.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$vozilo = Car::find($id);
		
		return view('admin.cars.show', ['vozilo' => $vozilo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vozilo = Car::find($id);
		$employees = Employee::orderBy('last_name','ASC')->get();
		
		return view('admin.cars.edit', ['vozilo' => $vozilo], ['employees' => $employees]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CarRequest $request, $id)
    {
        $vozilo = Car::find($id);
		$input = $request;
		
		$data = array(
			'proizvođač'  		=> $input['proizvođač'],
			'model'  			=> $input['model'],
			'registracija'  	=> $input['registracija'],
			'šasija' 			=> $input['šasija'],
			'prva_registracija' => $input['prva_registracija'],
			'zadnja_registracija' => $input['zadnja_registracija'],
			'trenutni_kilometri'  => $input['trenutni_kilometri'],
			'zadnji_servis'  	=> $input['zadnji_servis'],
			//'department_id' 	=> $input['department_id'],
			'user_id'  			=> $input['user_id']
		);
		
		$vozilo->updateCar($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci vozila');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.cars.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vozilo = Car::find($id);
        $vozilo->delete();
        
        $message = session()->flash('success', 'Vozilo je uspješno obrisano');
        
        return redirect()->route('admin.cars.index')->withFlashMessage($message);
    }
}
