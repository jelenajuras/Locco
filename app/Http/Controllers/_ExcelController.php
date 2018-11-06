<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use App\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Support\Facades\Input;
use DB;
use Excel;

class ExcelController extends Controller
{
	public function getExport()
	{
		$registration = Registration::all();
		
		Excel::create('Export djelatnici',function($excel) use($registration){
			$excel->sheet('Sheet 1',function($sheet) use($registration){
				$sheet->fromArray($registration);
			});
		})->export('xlsx');
	}

}
