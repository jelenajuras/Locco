@extends('layouts.admin')

@section('title', 'Ocjenjivanje zaposlenika')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}" type="text/css" >

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">

    <div class="page-header">
	    <h1>Ocjenjivanje po zaposlenicima za djelatnika {{ $employee->first_name . ' ' . $employee->last_name}}</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($evaluatingEmployees))
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Ime i prezime</th>
								<th>Anketa</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach($evaluatingEmployees as $evaluatingEmployee)
									<tr>
										<td>{{ $evaluatingEmployee->evaleated_employee['first_name'] . ' ' .  $evaluatingEmployee->evaleated_employee['last_name'] }}</td>
										<td>{{ $evaluatingEmployee->questionnaire['naziv'] . ' ' . $evaluatingEmployee->mjesec_godina }}</td>
									</tr>
							@endforeach
						</tbody>
					</table>
				@else
					{{'Nema podataka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@stop