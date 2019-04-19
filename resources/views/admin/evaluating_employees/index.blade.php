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
											<td>{{ $registration->employee['last_name'] . ' ' . $registration->employee['first_name'] }}
											
											</td>
											<td>	
												@if($evaluatingEmployees->where('employee_id',$registration->employee_id))
													@foreach($evaluatingEmployees->where('employee_id', $registration->employee_id)->unique('questionnaire_id') as $ev_empl)
													
														@foreach($evaluatingEmployees->where('employee_id', $registration->employee_id)->where('questionnaire_id', $ev_empl->questionnaire_id)->unique('mjesec_godina') as $mjesec)
														<a href="{{ route('admin.evaluating_employees.edit', ['id' => $registration->employee_id, 'ev_empl_id' => $ev_empl->id, 'questionnaire_id' => $ev_empl->questionnaire_id, 'mjesec_godina' => $mjesec->mjesec_godina] ) }}">{{ $ev_empl->questionnaire['naziv'] . '-' . $mjesec->mjesec_godina  }}</a><br>
														@endforeach
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