@extends('layouts.admin')

@section('title', 'Rezultati anketa')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}"/>
@section('content')
<div class="">
	<a class="btn btn-md" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
	</a>
	<section>
		@foreach($questionnaires as $questionnaire)
			<div class="table-responsive ">
				<h1>{{ $questionnaire->naziv }}</h1>
				<table id="table_id1" class="display" style="width: 100%;">
					<thead>
						<tr>
							<th>Djelatnik</th>
							@foreach($evaluatingGroups as $evaluatingGroup)
							<th>{{ $evaluatingGroup->naziv }}</th>
							@endforeach
							<th>Ukupna ocjena</th>
						</tr>
					</thead>
					<tbody id="myTable1">
						@foreach($registrations as $registration)
							@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
								<tr>
								<?php
									$ukupnaOcjena = 0;
								?>
									<td>{{ $registration->first_name . ' ' . $registration->last_name }}</td>
									@foreach($evaluatingGroups as $evaluatingGroup)
									
										<?php
											$i = 0;
											$rezultat = '';
											$ukupanRezultat = 0;
											
											foreach($evaluations as $evaluation){
												if($evaluation->employee_id == $registration->employee_id && $evaluation->group_id == $evaluatingGroup->id){
													$i++;
													$rezultat = number_format($evaluation->rating,2)*0.25 *number_format($evaluation->koef,2);
													$ukupanRezultat += $rezultat;
												}
											}
											if($ukupanRezultat === 0){
												$i = 1;
											} 
											$ukupanRezultat = number_format($ukupanRezultat / $i,2);
											$ukupnaOcjena += $ukupanRezultat
										?>
										<td>{{ $ukupanRezultat }}</td>
										
										
									@endforeach
									<td>{{ $ukupnaOcjena }}</td>
								</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>
		@endforeach
	</section>
</div>
@stop