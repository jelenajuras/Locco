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
	    <h1>Ocjenjivanje po zaposlenicima</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($registrations) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Ime i prezime zaposlenika</th>
								<th>Anketa</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach($registrations as $registration)
								@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
									<tr>
										<td>{{ $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] }} 
											<a href="{{ route('admin.evaluating_employees.create', ['id' => $registration->employee_id ] ) }}"><i class="fas fa-edit"></i></a>
											
										</td>
										<td>@if($evaluatingEmployees->where('employee_id',$registration->employee_id))
												@foreach($evaluatingEmployees->where('employee_id', $registration->employee_id)->unique('questionnaire_id') as $ev_empl)
													<a href="{{ route('admin.evaluating_employees.edit', ['id' => $registration->employee_id, 'ev_empl_id' => $ev_empl->id ] ) }}">"{{ $ev_empl->questionnaire['naziv'] }}"</a>
												@endforeach
											@endif
										</td>
									</tr>
								@endif
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