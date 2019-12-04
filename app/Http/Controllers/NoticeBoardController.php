<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\Employee_department;
use App\Http\Controllers\NoticeBoardController;
use Sentinel;
use DateTime;

class NoticeBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$datum = new DateTime('now');    /* današnji dan */
		$datum->modify('-30 day');
		
        $notices = Notice::where('type', null)->where('created_at', '>', $datum)->get();
        $notices = $notices->merge(Notice::where('type', 'uprava')->where('created_at', '>', $datum)->get());
        $notices = $notices->sortByDesc('created_at');
    //    $notices = Notice::orderBy('created_at','DESC')->get();
   
		$user = Sentinel::getUser();
        $employee = Employee::where('first_name',$user->first_name)->where('last_name',$user->last_name)->first();
        $reg_employee = Registration::where('employee_id', $employee->id)->first();
        $employee_departments = array();

        if ($reg_employee) {         
            if ( Sentinel::inRole('administrator')) {
                return view('admin.noticeBoard',['notices'=>$notices, 'employee' => $employee ]);
            } else {
                $departments = Employee_department::where('employee_id',$employee->id )->get();
                foreach( $departments as $department) {
                    array_push($employee_departments,$department->department_id);
                    array_push($employee_departments, 10); // odjel "svi"
                    if ($department->level == 2 ) {
                        array_push($employee_departments,$department->level1);
                    } 
                }
                return view('admin.noticeBoard',['notices'=>$notices, 'employee_departments' => $employee_departments, 'employee' => $employee, 'reg_employee' => $reg_employee ]);
            }
        } else {
            array_push($employee_departments, 10); // odjel "svi"
            $notices = $notices->where('to_department_id', 10);
            return view('admin.noticeBoard',['notices'=>$notices, 'employee_departments' => $employee_departments]);
        }

    }

    public function announcement()
    {
        $datum = new DateTime('now');    /* današnji dan */
        $datum->modify('-14 day');
        
        $notices = Notice::where('type','najava')->orderBy('created_at','DESC')->where('created_at', '>', $datum)->get();

        return view('admin.announcement',['notices'=>$notices]);
    }
}