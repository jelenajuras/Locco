@extends('layouts.admin')

@section('title', 'Rezultati anketa')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}"/>
@section('content')
<div class="evaluation">
	<a class="btn btn-md gumb_natrag" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
	</a>
	<section>
		
		<h1>{{ $employee->employee['first_name'] . ' ' . $employee->employee['last_name'] }}</h1>
		<table class="tbl_ocjene" id="">
			<thead>
				<tr>
					<th>Naziv grupe</th>
					<th>Osobna ocjena</th>
					<th>Ocjena direktora 
					@if($evaluation_D)
						@if(Sentinel::getUser()->id == 58)
						<a href="{{ route('admin.evaluations.edit',['id' => $evaluation_D->id, 'questionnaire_id' => $questionnaire->id]) }}"><i class="fas fa-edit"></i></a>
						@endif
					@endif
					</th>
					<th>Ocjena djelatnika {{ '(' . count($evaluatingEmployees->where('employee_id','!=', $employee->employee_id)) . ')'}} </th>
				</tr>
			</thead>
			<tbody>
				<?php
					$ukupnaOS = 0;
					$ukupnaDI = 0;
					$ukupnaDJ = 0;
				?>
				
				@foreach($evaluatingGroups as $evaluatingGroup)
					<?php
						$i = 0;
						$j = 0;
						$x = 0;  // broj pitanja
						$osobnaOcjena = 0;
						$osobnaOcjenaKON = 0;
						$ocjenaDirektora = 0;
						$ocjenaDirektoraKON = 0;
						$ocjenaOstali = 0;	
						$ocjenaOstaliKON = 0;	
					?>
					<tr>
					
						<td>{{  $evaluatingGroup->naziv }}</td>
						
							@foreach($evaluations->where('group_id', $evaluatingGroup->id ) as $evaluation)
								<?php
									$rezultat = 0;
									
									if($evaluation->user_id == $employee->employee_id && $evaluation->group_id == $evaluatingGroup->id){
										$i++;
										$rezultat = number_format($evaluation->rating,2)*0.25 *number_format($evaluation->koef,2);
										$osobnaOcjena += $rezultat;	
								
									} elseif($evaluation->user_id == '58' && $evaluation->group_id == $evaluatingGroup->id){
										$j++;
										$rezultat = number_format($evaluation->rating,2)*0.25 *number_format($evaluation->koef,2);	
										$ocjenaDirektora += $rezultat;
									
									}else {
										$x++;
										$rezultat = number_format($evaluation->rating,2)*0.25 * number_format($evaluation->koef,2);
										$ocjenaOstali += $rezultat;	
			
									}
									if($osobnaOcjena === 0){
										$i = 1;
									} 
									if($ocjenaDirektora === 0){
										$j = 1;
									} 
									if($ocjenaOstali === 0 ){
										$x = 1;
									} 
									$osobnaOcjenaKON =  number_format($osobnaOcjena / $i,2);
									$ocjenaDirektoraKON  = number_format($ocjenaDirektora / $j,2);
									$ocjenaOstaliKON  =  number_format($ocjenaOstali / $x,2);
								?>
							@endforeach
						<td>{{ $osobnaOcjenaKON  . ' (' . number_format($osobnaOcjenaKON/$evaluatingGroup->koeficijent*100,1) . '%' . ')' }}
						</td>
						<td>{{ $ocjenaDirektoraKON . ' (' . number_format($ocjenaDirektoraKON/$evaluatingGroup->koeficijent*100,1) . '%' . ')' }}</td>
						<td>{{ $ocjenaOstaliKON . ' (' . number_format($ocjenaOstaliKON/$evaluatingGroup->koeficijent*100,1) . '%' . ')' }}</td>
							
					</tr>
					@foreach($evaluatingQuestions->where('group_id',$evaluatingGroup->id) as $question)
						<tr>
							<td >
								{{ $question->opis }}
							</td>
							<?php
								if(! $evaluations->where('employee_id', $employee->employee_id)->where('user_id',$employee->employee_id)->where('group_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()){
									$ratingOS = 0; 
								} else {
									$ratingOS = $evaluations->where('employee_id', $employee->employee_id)->where('user_id', $employee->employee_id)->where('group_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()->rating;
								}
								if(! $evaluations->where('employee_id', $employee->employee_id)->where('user_id',58)->where('group_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()){
									$ratingDIR = 0;
								} else {
									$ratingDIR = $evaluations->where('employee_id', $employee->employee_id)->where('user_id', '58')->where('group_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()->rating;
								}
							?>
							
							<td>
							{{ number_format($ratingOS *0.25 * $evaluatingGroup->koeficijent,2) }}
							</td>
							<td>
							{{ number_format($ratingDIR *0.25 * $evaluatingGroup->koeficijent,2) }}
							</td>
							<td></td>
						</tr>
					@endforeach
					<?php
							$ukupnaOS += $osobnaOcjenaKON;
							$ukupnaDI += $ocjenaDirektoraKON;
							$ukupnaDJ += $ocjenaOstaliKON;
						?>		
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td>Ukupna ocjena</td>
					<td>{{ $ukupnaOS  . ' (' . $ukupnaOS/1*100 . '%' . ')'}}</td>
					<td>{{ $ukupnaDI . ' (' . $ukupnaDI/1*100 . '%' . ')' }}</td>
					<td>{{ $ukupnaDJ . ' (' . $ukupnaDJ/1*100 . '%' . ')' }}</td>
				</tr>
			</tfoot>
		</table>
	</section>
</div>
@stop