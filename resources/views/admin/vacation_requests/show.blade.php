@extends('layouts.admin')

@section('title', 'Duplico djelatnici')

@section('content')
<div class="">
    <div class="page-header">
        <h1>Godišnji odmori i izostanci - {{ $employee->first_name . ' ' . $employee->last_name }}</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive" id="tblData">
			@if(count($vacationRequests) > 0)
				<table id="table_id" class="display" style="width: 100%;">
					<thead>
						<tr>
							<th></th>
							<th class="disp_none">Datum zahtjeva</th>
							<th>Od - Do</th>
							<th>Zahtjev</th>
							<th>Napomena</th>
							<th>Odobreno</th>
							<th class="disp_none">Odobrio</th>
							<th class="disp_none">Datum odobrenja</th>
						</tr>
					</thead>
					@foreach($vacationRequests as $vacationRequest)
						<tbody id="myTable">
							<tr>
								<td>
								@if(Sentinel::inRole('administrator'))
									<a href="{{ route('admin.vacation_requests.edit', $vacationRequest->id) }}" class="">
										<span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
								@endif
								</td>
								<td class="disp_none">{{ date('d.m.Y.', strtotime( $vacationRequest->created_at)) }}</td>
								<td>{{ date('d.m.Y.', strtotime( $vacationRequest->GOpocetak)) }}<br>
								<?php 
									$brojDana =1;
								?>
								@if($vacationRequest->GOzavršetak != $vacationRequest->GOpocetak )
								{{ date('d.m.Y.', strtotime( $vacationRequest->GOzavršetak)) }}
								<?php 
									$begin = new DateTime($vacationRequest->GOpocetak);
									$end = new DateTime($vacationRequest->GOzavršetak);
									$interval = DateInterval::createFromDateString('1 day');
									$period = new DatePeriod($begin, $interval, $end);
									foreach ($period as $dan) {
										if(date_format($dan,'N') < 6 &&
										!(date_format($dan,'d') == '01' & date_format($dan,'m') == '01') &&
										!(date_format($dan,'d') == '06' & date_format($dan,'m') == '01') &&
										!(date_format($dan,'d') == '01' & date_format($dan,'m') == '05') &&
										!(date_format($dan,'d') == '22' & date_format($dan,'m') == '06') &&
										!(date_format($dan,'d') == '25' & date_format($dan,'m') == '06') &&
										!(date_format($dan,'d') == '15' & date_format($dan,'m') == '08') &&
										!(date_format($dan,'d') == '05' & date_format($dan,'m') == '08') &&
										!(date_format($dan,'d') == '08' & date_format($dan,'m') == '10') &&
										!(date_format($dan,'d') == '01' & date_format($dan,'m') == '11') &&
										!(date_format($dan,'d') == '25' & date_format($dan,'m') == '12') &&
										!(date_format($dan,'d') == '26' & date_format($dan,'m') == '12') &&
										!(date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018') &&
										!(date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018') &&
										!(date_format($dan,'d') == '22' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2019') &&
										!(date_format($dan,'d') == '20' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2019') &&
										!(date_format($dan,'d') == '13' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2020') &&
										!(date_format($dan,'d') == '11' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2020')){
											$brojDana += 1;
										}
									}
								?>
								@elseif($vacationRequest->zahtjev != 'GO') 
									{{ date('H:i', strtotime( $vacationRequest->GOpocetak. ' '. $vacationRequest->vrijeme_od))  }} - {{  date('H:i', strtotime( $vacationRequest->GOpocetak. ' '. $vacationRequest->vrijeme_do)) }}
								@endif
								</td>
								<td>{{ $vacationRequest->zahtjev . ', ' . $brojDana . ' dana'}} 
								</td>
								<td>{{ $vacationRequest->napomena }}</td>
								<td>{{ $vacationRequest->odobreno }}  {{ $vacationRequest->razlog  }}</td>
								<td class="disp_none">{{ $vacationRequest->authorized['first_name'] . ' ' . $vacationRequest->authorized['last_name']}}</td>
								<td class="disp_none">
								@if( $vacationRequest->datum_odobrenja != "")
								{{ date('d.m.Y.', strtotime( $vacationRequest->datum_odobrenja))}}
								@endif
								</td>
								
							</tr>
						</tbody>
					@endforeach
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
