<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;
use App\Models\Ad;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\EmployeeTermination;
use App\Http\Request\AdRequest;

class AdController extends Controller
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
		$ads = Ad::where('category_id',$request->id )->get();
		$category_id = $request->id;
		
		return view('admin.ads.index', ['ads'=>$ads, 'category_id'=>$category_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$registrations = Registration::get();
		$category_id = $request->category_id;
		$employeeTerminations = EmployeeTermination::get();
		
		return view('admin.ads.create', ['registrations'=>$registrations, 'employeeTerminations'=>$employeeTerminations, 'category_id'=>$category_id]);
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
		$user = Sentinel::getUser();
		$employee = Employee::where('last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$data = array(
			'employee_id'  		=>  $employee->id,
			'category_id'  		=> $input['category_id'],
			'subject'  			=> $input['subject'],
			'description'  		=> $input['description'],
			'price'  			=> $input['price']
		);
		
		$ad = new Ad();
		$ad->saveAd($data);
		
		$message = session()->flash('success', 'Oglas je spremljena');

		return redirect()->route('admin.ads.index',['id'=> $input['category_id']])->withFlashMessage($message);
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
		$ad = Ad::find($id);

		return view('admin.ads.edit', ['ad'=>$ad]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = Ad::find($id);
		$ad->delete();
		
		$message = session()->flash('success', 'Oglas je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
