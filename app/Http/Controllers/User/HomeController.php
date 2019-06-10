<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GodisnjiController;
use App\Models\Registration;
use App\Models\Employee;
use App\Models\Employee_department;
use App\Models\VacationRequest;
use App\Models\EffectiveHour;
use App\Models\AfterHour;
use App\Models\Questionnaire;
use App\Models\Evaluation;
use App\Models\EvaluatingGroup;
use App\Models\EvaluatingQuestion;
use App\Models\Education;
use App\Models\EvaluationTarget;
use App\Models\Presentation;
use App\Models\Ad;
use Sentinel;
use DateTime;

class HomeController extends GodisnjiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = Sentinel::getUser();
		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		if($employee) {
			$employeeDepartments = Employee_department::where('employee_id', $employee->id)->get();
			$afterHours = AfterHour::get();
			
			$registration = Registration::where('registrations.employee_id', $employee->id)->first();
			$ech = EffectiveHour::where('employee_id', $employee->id)->first();
			$datum = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($datum,'Y');
		
			$zahtjeviD = VacationRequest::orderBy('GOpocetak','DESC')->take(30)->get();
			
			// ANKETE
			$questionnaires = Questionnaire::get();
			$evaluatingGroups = EvaluatingGroup::get();
			$evaluatingQuestions = EvaluatingQuestion::get();
			$evaluationTargets = EvaluationTarget::where('employee_id',$employee->id)->orderBy('created_at','DESC')->get();
			$evaluations = Evaluation::where('employee_id',$employee->id)->get();
			
			// Edukacija
			$educations = Education::where('status','aktivna')->get();
			$presentations = Presentation::where('status','aktivan')->get();
			$ads = Ad::get();
			
			return view('user.home', ['registration' => $registration,'ech' => $ech,'employee' => $employee,'zahtjeviD' => $zahtjeviD,'ova_godina' => $ova_godina,'afterHours' => $afterHours,'questionnaires' => $questionnaires, 'evaluatingGroups' => $evaluatingGroups, 'evaluatingQuestions' => $evaluatingQuestions, 'educations' => $educations, 'evaluationTargets' => $evaluationTargets, 'employeeDepartments' => $employeeDepartments, 'evaluations' => $evaluations, 'presentations' => $presentations, 'ads' => $ads]);
			
		} else {
			return view('user.home');
		}
		
    }
}
