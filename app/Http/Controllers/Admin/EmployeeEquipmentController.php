<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmployeeEquipment;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeEquipmentRequest;
use App\Http\Controllers\Controller;
use Sentinel;
use Session;
use App\Models\Employee;
use App\Models\Registration;
use App\Models\Equipment;
use App\Models\Users;
use App\Models\Work;
use PDF;
use DB;
use Mail;


class EmployeeEquipmentController extends Controller
{
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
        $employeeEquipments = EmployeeEquipment::orderBy('id','ASC')->paginate(50);

		return view('admin.employee_equipments.index',['employeeEquipments'=>$employeeEquipments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $employee = $request->id;
		$employees = Employee::get();
		
		return view('admin.employee_equipments.create')->with('employees', $employees)->with('employee', $employee);
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
		
		//dd($zaduzene_osobe);
		
		if($request->equipment_id1){
			foreach($input['equipment_id1'] as $oprema){
				$data = array(
					'employee_id'  	 => $input['employee_id'],
					'datum_zaduzenja'=> date("Y-m-d", strtotime($input['datum_zaduzenja'])),
					'equipment_id'   => $oprema,
					'kolicina'	     => Equipment::where('id',$oprema)->value('količina_monter'),
					'napomena'     	 => $input['napomena']
				);
				$employeeEquipment = new EmployeeEquipment();
				$employeeEquipment->saveEmployeeEquipment($data);
			}
		}
		if($request->equipment_id2){
			foreach($input['equipment_id2'] as $oprema){
				$data = array(
					'employee_id'  	 => $input['employee_id'],
					'datum_zaduzenja'=> date("Y-m-d", strtotime($input['datum_zaduzenja'])),
					'equipment_id'   => $oprema,
					'kolicina'	     => Equipment::where('id',$oprema)->value('količina_inženjer'),
					'napomena'     	 => $input['napomena']
				);
				$employeeEquipment = new EmployeeEquipment();
				$employeeEquipment->saveEmployeeEquipment($data);
			}
		}
		
		/***************send email************/
		/*$input_oprema = $request->only('equipment_id1','equipment_id2');
		$equipments = Equipment::get();
		$zaduzene_osobe = Equipment::distinct()->get(['User_id']);
		
		$djelatnik = Employee::where('id',$input['employee_id'])->first();*/
		
		/*********privremeno za probu ***************/
		/*$email = $djelatnik->email; 
		$email_proba = 'jelena.juras@duplico.hr'; */
		/*******email zadužene osobe ******************/

		
		/*foreach($zaduzene_osobe as $zaduzena_osoba){
			$user_mail = Users::select('id','email')->where('id',$zaduzena_osoba->User_id)->value('email');
				Mail::queue(
					'email.oprema',
					['djelatnik' => $djelatnik,'input_oprema' => $input_oprema,'equipments' => $equipments,'user_mail' => $user_mail,'napomena' => $input['napomena']],
					function ($message) use ($user_mail) {
						$message->to($user_mail)
							->subject('Novi djelatnik - radna oprema');
					}
				);
			}*/
		
		$message = session()->flash('success', 'Oprema je zadužena');
		
		return redirect()->route('admin.employee_equipments.index')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$datum = EmployeeEquipment::orderBy('datum_zaduzenja', 'desc')->value('datum_zaduzenja');
		
		$employee = Employee::find($id);
		$employeeEquipment = EmployeeEquipment::where('datum_zaduzenja',$datum)->get();
		$equipments = Equipment::get();
		$radnoMjesto = Work::where('id',$employee->radnoMjesto_id)->first();
		
		//dd($employeeEquipment);
		
		return view('admin.employee_equipments.show', ['employee' => $employee])->with('employee', $employee)->with('equipments', $equipments)->with('employeeEquipment', $employeeEquipment)->with('radnoMjesto', $radnoMjesto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employeeEquipments = EmployeeEquipment::find($id);
		
		return view('admin.employee_equipments.edit', ['employeeEquipments' => $employeeEquipments]);
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
        $employeeEquipment = EmployeeEquipment::find($id);
		$input = $request->except(['_token']);
		//dd($input);
		if($request->equipment_id1){
			foreach($input['equipment_id1'] as $oprema){
				$data = array(
					'employee_id'  	 => $input['employee_id'],
					'datum_povrata'=> date("Y-m-d", strtotime($input['datum_povrata'])),
					'equipment_id'   => $oprema,
					'kolicina'	     => Equipment::where('id',$oprema)->value('količina_monter'),
					'napomena'     	 => $input['napomena']
				);
				$employeeEquipment->updateEmployeeEquipment($data);
			}
		}
		if($request->equipment_id2){
			foreach($input['equipment_id2'] as $oprema){
				$data = array(
					'employee_id'  	 => $input['employee_id'],
					'datum_povrata'=> date("Y-m-d", strtotime($input['datum_zaduzenja'])),
					'equipment_id'   => $oprema,
					'kolicina'	     => Equipment::where('id',$oprema)->value('količina_inženjer'),
					'napomena'     	 => $input['napomena']
				);
				$employeeEquipment->updateEmployeeEquipment($data);
			}
		}
				
		$message = session()->flash('success', 'Podaci su ispravljeni');
		
		return redirect()->route('admin.employee_equipments.index')->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeeEquipment = EmployeeEquipment::find($id);
		$employeeEquipment->delete();
		
		$message = session()->flash('success', 'Oprema je obrisana.');
		
		return redirect()->route('admin.employee_equipments.index')->withFlashMessage($message);
    }
	
	public function zaduzenje_pdf($id) 
	{
	$registration = Registration::find($id);
	$pdf = PDF::loadView('admin.employee_equipments.show', compact('employee_equipments'));
	return $pdf->download('Zaduženje_'. $registration->id .'.pdf');

	}
}
