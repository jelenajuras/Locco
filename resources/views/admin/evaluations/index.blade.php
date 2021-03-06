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
	<?php
		$x = 0;
	?>
		@foreach($questionnaires as $questionnaire)
			@foreach($mjesec_godina as $mjesec)
			<?php
				$x++;
			?>
				<div class="table-responsive ">
					<details open>
						<summary>{{ $questionnaire->naziv . ' - ' .  $mjesec->mjesec_godina}}</summary>
					
						<table id="table_id" class="display" style="width: 100%;">
							<thead>
								<tr>
									<th>Djelatnik</th>
									<th>Dao ocjena</th>
									<th>Dobio ocjena</th>
							
									<th>Ukupna ocjena</th>
								</tr>
							</thead>
							<tbody>
								@foreach($registrations as $registration)
									@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
										<tr>
										<?php
											$ukupnaOcjena = 0;
											$ukupanRezultat = 0;
											$i = 0;
										?>
											<td><a href="{{ route('admin.evaluations.show',['employee_id' =>  $registration->employee_id, 'questionnaire_id' =>  $questionnaire->id, 'mjesec_godina' =>$mjesec->mjesec_godina ] ) }}">{{ $registration->first_name . ' ' . $registration->last_name }}</a></td>
											<td>{{ count($evaluatingEmployee->where('employee_id', $registration->employee_id))  }}</td>
											<td>{{ count($evaluations->where('employee_id', $registration->employee_id)) /25 }}</td>
											<!--	@foreach($evaluations->where('questionnaire_id',$questionnaire->id)->where('employee_id', $registration->employee_id) as $evaluation)
													<?php
														$rezultat = '';
														$ukupanRezultat = 0;
													
														$mjesec_godina = substr($evaluation->datum,0,7);
														if($mjesec_godina == $mjesec->mjesec_godina){
															$i++;
															$rezultat = number_format($evaluation->rating,2)*0.25 *number_format($evaluation->koef,2);
															$ukupanRezultat += $rezultat;	
														}
														if($ukupanRezultat === 0){
															$i = 1;
														} 
														$ukupanRezultat = number_format($ukupanRezultat / $i, 2);
														$ukupnaOcjena += $ukupanRezultat ;
												?>
												
												@endforeach-->
											<td></td>
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</details>
				</div>
			@endforeach
		@endforeach
	</section>
</div>
@stop