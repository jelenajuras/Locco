<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\EffectiveHourRequest;
use App\Http\Controllers\Controller;
use App\Models\EffectiveHour;
use App\Models\Registration;

class EffectiveHourController extends Controller
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
        $registrations = Registration::join('employees','registrations.employee_id','employees.id')->select('registrations.*','employees.first_name','employees.last_name')->orderBy('last_name','ASC')->get();
		$effectiveHours = EffectiveHour::join('registrations','effective_hours.employee_id','registrations.employee_id')->select('registrations.*','effective_hours.effective_cost', 'effective_hours.brutto')->get();
		
		return view('admin.effective_hours.index',['registrations' => $registrations, 'effectiveHours' => $effectiveHours]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EffectiveHourRequest $request)
    {

        $input = $request;
        
        foreach($input['employee_id'] as $key => $employee){
            $effectiveHour_employee = EffectiveHour::where('employee_id',  $employee)->first();
 
            foreach($input['effective_cost'] as $key2 => $value2){
                if($key2 === $key){
                   $efc = $value2;
                }
            }
            foreach($input['brutto'] as $key3 => $value3){
                if($key3 === $key){
                   $brutto = $value3;
                }
            }

            $data = array(
                'employee_id'     =>  $employee,
                'effective_cost'  => str_replace(',','.', $efc),
                'brutto'   		  => str_replace(',','.', $brutto),
            );

            if( $brutto != '' || $efc != '' ) {
                if($effectiveHour_employee == null ) {
                    $effectiveHour = new EffectiveHour();
                    $effectiveHour->saveEffectiveHour($data);
                } else {
                    $effectiveHour_employee->updateEffectiveHour($data);
                }
            }
        }
		
		$message = session()->flash('success', 'Uneseno!');
		
		return redirect()->back()->withFlashMessage($message);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EffectiveHourRequest $request, $id)
    {
        $effectiveHour = EffectiveHour::find($id);
		$input = $request;
		
		$data = array(
			'employee_id'     => $input['employee_id'],
			'effective_cost'  => str_replace(',','.', $input['effective_cost']),
			'brutto'   		  => str_replace(',','.', $input['brutto']),
		);
		
		$effectiveHour->updateEffectiveHour($data);
		
		$message = session()->flash('success', 'Uspješno su ispravljeni podaci');
		
		return redirect()->back()->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
