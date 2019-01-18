@extends('layouts.admin')

@section('title', 'Duplico djelatnici')
<?php
	use App\Http\Controllers\GodisnjiController;
?>
@section('content')
<div class="row">
    <div class="page-header">
        <h1>Godišnji odmori i izostanci - {{ $employee->first_name . ' ' . $employee->last_name }}</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <div class="table-responsive">
			@if(count($vacationRequests) > 0)
				<table id="table_id" class="display" style="width: 100%;">
					<thead>
						<tr>
							<th class="not-export-column">Opcije</th>
							<th>Od - Do</th>
							<th>Zahtjev</th>
							<th>Napomena</th>
							<th>Odobreno</th>
							<th >Odobrio</th>
							<th>Datum odobrenja</th>
						</tr>
					</thead>
					
					<tbody id="myTable">
					@foreach($vacationRequests as $vacationRequest)
						<?php 
							$brojDana = GodisnjiController::daniGO(['GOpocetak' => $vacationRequest->GOpocetak, 'GOzavršetak' => $vacationRequest->GOzavršetak] );
							$vrijeme = GodisnjiController::izlazak(['od' => $vacationRequest->vrijeme_od, 'do' => $vacationRequest->vrijeme_do] );
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
							<td>{{ date('d.m.Y.', strtotime( $vacationRequest->GOpocetak)) }}
								
								@if($vacationRequest->GOzavršetak != $vacationRequest->GOpocetak )
								{{ ' - ' . date('d.m.Y.', strtotime( $vacationRequest->GOzavršetak)) }}
								
								@elseif($vacationRequest->zahtjev != 'GO') 
									{{ date('H:i', strtotime( $vacationRequest->GOpocetak. ' '. $vacationRequest->vrijeme_od))  }} - {{  date('H:i', strtotime( $vacationRequest->GOpocetak. ' '. $vacationRequest->vrijeme_do)) }}
								@endif
							</td>
							<td>{{ $vacationRequest->zahtjev }}
								@if($vacationRequest->zahtjev == 'Izlazak')
									{{'- ' . $vrijeme . ' h' }}
								@else
									 {{'- ' . $brojDana . ' dana' }}
								@endif
							</td>
							<td>{{ $vacationRequest->napomena }}</td>
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

				@else
					{{'Nema podataka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
<!--
<div class="uputa">
	<p>*** Napomena:</p>
	<p>Sukladno radnopravnim propisima RH:<br>
		- radnik ima za svaku kalendarsku godinu pravo na godišnji odmor od najmanje 20 radnih dana,<br>
		- radnik ima pravo na dodatne dane godišnjeg odmora (po 1 radni dan za svakih navršenih četiri godina <br>radnog staža; po 2 radna dana radniku roditelju s dvoje ili više djece do 7 godina života),<br>
		- ukupno trajanje godišnjeg odmora radnika ne može iznositi više od 25 radnih dana.<br>
		- razmjerni dio godišnjeg odmora za tekuću godinu utvrđuje se u trajanju od 1/12 godišnjeg odmora za <br>svaki mjesec trajanja radnog odnosa u Duplicu u tekućoj godini.<br>

	Za eventualna pitanja, molimo kontaktirati pravni odjel na pravni@duplico.hr.<br>
	</p>
</div>-->

@stop
