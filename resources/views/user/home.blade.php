@extends('layouts.admin')

@section('title', 'Naslovnica')

@section('content')
@if(Sentinel::check())
	<div class="">
		<h2>{{ $user->first_name . ' ' . $user->last_name }}</h2>
		<h4><b>Efektivna cijena sata: </b>{{  number_format($registration->ech['effective_cost'],2,",",".") . ' kn' }}</h4>
		<h4><b>Godišnja brutto plaća: </b>{{  number_format($registration->ech['brutto'],2,",",".") . ' kn' }}</h4>
		<div class="dashboard_box1">
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.noticeBoard') }}"  ><span>Oglasna ploča</span></a>
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.vacation_requests.index') }}"  ><span>Godišnji odmor i izostanci</span></a>
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.posts.index') }}" ><span>Poruke</span></a>
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.afterHours.index') }}"><span>Prekovremeni rad</span></a>
				</div>
			</div>
			
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a href="{{ route('admin.registrations.show', $registration->id) }}">
						<span>Opći podaci<br>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</span></a>
				</div>
			</div>
		</div>
	</div>
	@if (Sentinel::inRole('administrator'))
		<div class="dashboard_box" style="overflow-x:auto;">
			<button class="collapsible">Odobreni zahtjevi zaposlenika</button>
			<div class="content">
				<table class="zahtjevi2">
					<thead>
						<tr>
							<th>Ime i prezime</th>
							<th>Od - Do</th>
							<th>Zahtjev</th>
							<th>Napomena</th>
							<th>Odobreno</th>
							
						</tr>
					</thead>
					@foreach($zahtjeviD as $zahtjevD)
						<tbody>
							@if(date('Y', strtotime( $zahtjevD->GOzavršetak)) == $ova_godina)
							<tr><td>{{ $zahtjevD->employee['first_name'] . ' ' . $zahtjevD->employee['last_name'] }}</td>
								<td>{{ date('d.m.Y.', strtotime( $zahtjevD->GOpocetak)) }}<br>
								@if($zahtjevD->GOzavršetak != $zahtjevD->GOpocetak ){{ date('d.m.Y.', strtotime( $zahtjevD->GOzavršetak)) }}
								@elseif( $zahtjevD->zahtjev != 'GO')
								{{ date('H:i', strtotime( $zahtjevD->GOpocetak. ' '. $zahtjevD->vrijeme_od))  }} - {{  date('H:i', strtotime( $zahtjevD->GOpocetak. ' '. $zahtjevD->vrijeme_do)) }}
								@endif
								</td>
								<?php 
									$brojDana =1;
									$begin = new DateTime($zahtjevD->GOpocetak);
									$end = new DateTime($zahtjevD->GOzavršetak);
									$interval = DateInterval::createFromDateString('1 day');
									$period = new DatePeriod($begin, $interval, $end);
									foreach ($period as $dan) {
										if(date_format($dan,'N') < 6){
											$brojDana += 1;
										}
									}
								?>
								<td>{{ $zahtjevD->zahtjev }}
									@if($zahtjevD->zahtjev == 'GO') 
										{{ $brojDana . ' dana ' }}
									@endif
								</td>
								<td>{{ $zahtjevD->napomena }}</td>
								<td>{{ $zahtjevD->odobreno }} {{ $zahtjevD->razlog  }}</td>
							</tr>
							@endif
						</tbody>
					@endforeach
				</table>
			</div>
		</div>
	@endif
@else
	<div class="jumbotron">
		<h1>Welcome, Guest!</h1>
		<p>You must login to continue.</p>
		<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">Log In</a></p>
	</div>
@endif

@stop
