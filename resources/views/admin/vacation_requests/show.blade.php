@extends('layouts.admin')

@section('title', 'Izostanci djelatnika' . ' - ' . $registration->employee['first_name'] . ' ' .  $registration->employee['last_name'] )
<?php
	use App\Http\Controllers\GodisnjiController;
	$ukupna_razlika = 0;
	$iskorišteno_GO = 0;
	$iskorišteno_SLD = 0;
	$iskorišteno_GO_ova_godina = 0;
	$iskorišteno_GO_prosla_godina = 0;
?>
@section('content')
<div class="row">
		<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
			<i class="fas fa-angle-double-left"></i>
			Natrag
		</a>
    <div class="page-header">
        <h1>Godišnji odmori i izostanci - {{ $registration->employee['first_name'] . ' ' .  $registration->employee['last_name'] }}</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <div class="table-responsive">
			@if(count($vacationRequests) > 0)
				<table id="table_id" class="display sort_2_desc" style="width: 100%;">
					<thead>
						<tr>
							<th class="not-export-column">Opcije</th>
							<th>Od</th>
							<th>Do</th>
							<th>Period</th>
							<th>Vrijeme</th>
							<th>Zahtjev</th>
							<th>Napomena</th>
							<th>Odobrio voditelj</th>
							<th>Odobreno</th>
							<th >Odobrio</th>
							<th>Datum odobrenja</th>
						</tr>
					</thead>
					<tbody id="myTable">
					@foreach($vacationRequests as $vacationRequest)
						<?php 
							$brojDana = GodisnjiController::daniGO(['start_date' => $vacationRequest->start_date, 'end_date' => $vacationRequest->end_date] );
							$vrijeme = GodisnjiController::izlazak(['od' => $vacationRequest->start_time, 'do' => $vacationRequest->end_time] );
							if($vacationRequest->zahtjev == 'GO' && $vacationRequest->odobreno == 'DA' ) {
								$iskorišteno_GO += $brojDana;
							}
							if($vacationRequest->zahtjev == 'SLD' && $vacationRequest->odobreno == 'DA' ){
								$iskorišteno_SLD += $brojDana;
							}
	
						?>
						<tr>
							<td class="not-export-column">
							@if(Sentinel::inRole('administrator'))
								<a href="{{ route('admin.vacation_requests.edit', $vacationRequest->id) }}" class="width_33">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
								<a href="{{ route('admin.VacationRequest', ['id' => $vacationRequest->id] ) }}" title="Print zahtjeva" target="_blank" class="width_33">
									<i class="far fa-file"></i></a> 
								<a href="{{ route('admin.vacation_requests.destroy', $vacationRequest->id) }}" class=" action_confirm width_33" data-method="delete" data-token="{{ csrf_token() }}">
									<i class="far fa-trash-alt"></i>
								</a>
							@endif
							</td>
							<td>{{ date('Y.m.d.', strtotime( $vacationRequest->start_date)) }}</td>
							<td>{{ date('Y.m.d.', strtotime( $vacationRequest->end_date)) }}</td>
							
							<td>@if($vacationRequest->zahtjev == 'Izlazak')
									{{$vrijeme . ' h' }}
								@else
									 {{$brojDana . ' dana' }}
								@endif</td>
							<td>	
								@if($vacationRequest->zahtjev == 'Izlazak') 
									{{ date('H:i', strtotime($vacationRequest->start_time))  }} - {{  date('H:i', strtotime($vacationRequest->end_time)) }}
								@endif
							</td>
							<td>{{ $vacationRequest->zahtjev }}
								
							</td>
							<td>{{ $vacationRequest->napomena }}</td>
							<td>{{ $vacationRequest->odobreno2 }}</td>
							<td>{{ $vacationRequest->odobreno }}  {{ $vacationRequest->razlog  }}</td>
							<td>{{ $vacationRequest->authorized['first_name'] . ' ' . $vacationRequest->authorized['last_name']}}</td>
							<td>
								@if( $vacationRequest->datum_odobrenja != "")
								{{ date('d.m.Y.', strtotime( $vacationRequest->datum_odobrenja))}}
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="page-footer">
					<h4>Ukupno iskorišteno dana GO {{ $iskorišteno_GO }}</h4>
					<h4>Ukupno iskorišteno slobodnih dana {{ $iskorišteno_SLD }} </h4>
				</div>
				@else
					{{'Nema podataka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@if(Sentinel::inRole('administrator'))
  <div class="row" id="printarea">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		 <div class="page-header">
			<h2>Prekovremeni sati</h2>
		</div>
           <div class="table-responsive">
				@if(count($afterHours) > 0)
					 <table id="table_id1" class="display" style="width: 100%;">
						<thead >
							<tr>
								<th style="border-bottom: 1px double #ccc;">Djelatnik</th>
								<th style="border-bottom: 1px double #ccc;">Datum</th>
								<th style="border-bottom: 1px double #ccc;">Vrijeme</th>
								<th style="border-bottom: 1px double #ccc;">Napomena</th>
								<th style="border-bottom: 1px double #ccc;">Odobrio</th>
								<th style="border-bottom: 1px double #ccc;">Odobreno sati</th>
								<th  style="border-bottom: 1px double #ccc;" class="not-export-column">Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable1">
						<?php $ukupnosati = 0; ?>
							@foreach ($afterHours as $afterHour)
								<?php
									if($afterHour->odobreno_h ) {
										$razlika_vremena = $afterHour->odobreno_h;
									} else {
										$vrijeme_1 = new DateTime($afterHour->start_time );
										if($afterHour->end_time == '00:00:00') {
											$vrijeme_2 = new DateTime('23:59:59');  /* vrijeme do */
										} else {
											$vrijeme_2 = new DateTime($afterHour->end_time);  /* vrijeme do */
										}
										
										$razlika_vremena = $vrijeme_2->diff($vrijeme_1);
										$razlika_vremena = $razlika_vremena->format('%H:%I');
									}

									// konvert vremena u decimalan broj
									$hm = explode(":", $razlika_vremena);
									$razlika_vremena = $hm[0] + ($hm[1]/60);
									
									$dan_prekovremeni = new DateTime($afterHour->datum);
									if(date_format($dan_prekovremeni,'N') == 6) {
										$razlika_vremena = $razlika_vremena * 1.3;
									} elseif (date_format($dan_prekovremeni,'N') == 7) {
										$razlika_vremena = $razlika_vremena * 1.4;
									} else {
										$razlika_vremena = $razlika_vremena;
									}
									if( $afterHour->odobreno == "DA") {
										$ukupnosati += round($razlika_vremena, 1, PHP_ROUND_HALF_DOWN);
									}
								?>
								<tr>
									<td>{{ $afterHour->employee['first_name'] . ' ' . $afterHour->employee['last_name'] }}</td>
									<td>{{ date('Y-m-d', strtotime($afterHour->datum )) }}</td>
									<td>{{ $afterHour->start_time . '-' . $afterHour->end_time}}</td>
									<td>{{ $afterHour->napomena }}</td>
									<td>{!! $afterHour->odobreno == "DA" ? round($razlika_vremena, 1, PHP_ROUND_HALF_DOWN) : '' !!}</td>
									<td>{{ $afterHour->odobreno }}</td>
									<td>
										<a href="{{ route('admin.confirmationAfter_show', ['id' => $afterHour->id]) }}" class="btn" title="Odobri">
											<i class="fas fa-check"></i>
										</a>
										<a href="{{ route('admin.afterHours.edit', $afterHour->id) }}" class="btn" title="Ispravi">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											
										</a>
										<a href="{{ route('admin.afterHours.destroy', $afterHour->id) }}" class="btn action_confirm {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}" data-method="delete" data-token="{{ csrf_token() }}" title="Obriši">
											<i class="far fa-trash-alt"></i>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					@php
					
					@endphp
					<div class="page-footer">
						<h4>Ukupno prekovremenih sati {{ round(GodisnjiController::prekovremeni_sati( $registration ),0,1) }}</h4>
						<h4>Ukupno izlazaka {{ GodisnjiController::izlasci_ukupno( $registration ) }} </h4>
						<h4>Ukupno slobodnih dana {{ GodisnjiController::prekovremeni_bez_izlazaka( $registration ) }} </h4>
						<h4>Preostalo slobodnih dana  {{ GodisnjiController::prekovremeni_bez_izlazaka( $registration ) - $iskorišteno_SLD }} </h4>
					</div>
		
				@else
					{{'Nema neodobrenih evidencija!'}}
				@endif
            </div>
        </div>
    </div>
@endif

@stop
