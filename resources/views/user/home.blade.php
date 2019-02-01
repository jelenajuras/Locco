@extends('layouts.admin')

@section('title', 'Naslovnica')

@section('content')
@if(Sentinel::check())
	<div class="row">
		<div class="ech">
			<p>{{ $user->first_name . ' ' . $user->last_name }} ukupan trošak Tvoje godišnje plaće iznosi <b>{{  number_format($ech['brutto'],2,",",".") . ' kn' }}</b>.</p>
			<p>Efektivna cijena Tvog sata rada u Duplicu iznosi po satu: <b>{{  number_format($ech['effective_cost'],2,",",".") . ' kn' }}</b>, a obračunata je kao stvarno provedeno vrijeme na radu kroz bruto troškove godišnje plaće, a sve za redovan rad.</p>
		</div>
		<div class="dashboard_box1">
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.noticeBoard') }}"  ><span>Oglasna ploča</span></a>
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.vacation_requests.index') }}"><span>Godišnji odmor i izostanci</span></a>
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.shedulePost') }}" ><span>Zahtjev za rasporedom</span></a>
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a class="" href="{{ route('admin.posts.index') }}" ><span>Poruke</span></a>
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					@if (Sentinel::inRole('administrator'))
					<a class="" href="{{ route('admin.afterHours.index') }}"><span>Prekovremeni rad</span></a>
					@else
					<a class="" href="{{ route('admin.afterHours.create') }}"><span>Prekovremeni rad</span></a>
					@endif
				</div>
			</div>
			<div class="BTNbox">
				<div class="dashboard_box2">
					<a href="{{ route('admin.registrations.show', $registration->id) }}">
						<span>Opći podaci<br>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</span></a>
				</div>
			</div>
			
			@foreach($questionnaires as $questionnaire)
				<div class="BTNbox">
					<div class="dashboard_box2 anketa">
						<a href="{{ route('admin.questionnaires.show', $questionnaire->id) }}">
							<span>Anketa<br> <br>{{$questionnaire->naziv}}</span></a>
					</div>
				</div>
			@endforeach
			
			@if (Sentinel::inRole('uprava') || Sentinel::getUser()->last_name == 'Barberić')
				<div class="BTNbox">
					<div class="dashboard_box2">
						<a href="{{ route('admin.notices.create') }}">
							<span>Nova obavijest</span></a>
					</div>
				</div>
			@endif
		</div>
	</div>
	<div class="row">
	@if(Sentinel::inRole('administrator'))
		@if(count($afterHours->where('odobreno', '')) > 0)
			<div class="dashboard_box" style="overflow-x:auto;">
				<button class="collapsible">Neodobreni prekovremeni rad</button>
				<div class="content">
					<table class="zahtjevi2">
						<thead>
							<tr>
								<th>Odobri</th>
								<th>Ime i prezime</th>
								<th>Od - Do</th>
								<th>Napomena</th>
							</tr>
						</thead>
						@foreach($afterHours as $afterHour)
							@if($afterHour->odobreno == '')
								<tbody>
									<tr>
										<td>
											<a class="" href="{{ route('admin.confirmationAfter_show', ['id' => $afterHour->id]) }}"><i class="far fa-check-square"></i></a>
										</td>
										<td>{{ $afterHour->employee['first_name'] . ' ' . $afterHour->employee['last_name'] }}</td>
										<td>{{ date('d.m.Y.', strtotime( $afterHour->datum)) }}<br>
										{{ date('H:i', strtotime( $afterHour->vrijeme_od)) . ' - '. date('H:i', strtotime( $afterHour->vrijeme_do))  }}
										
										</td>
										<td>{{ $afterHour->napomena }}</td>
									</tr>
									
								</tbody>
							@endif
						@endforeach
					</table>
				</div>
			</div>
		@endif
		@if(count($zahtjeviD->where('odobreno', '')) > 0)
		<div class="dashboard_box" style="overflow-x:auto;">
			<button class="collapsible">Neodobreni zahtjevi izostanaka</button>
			<div class="content">
				<table class="zahtjevi2">
					<thead>
						<tr>
							<th>Odobri</th>
							<th>Ime i prezime</th>
							<th>Od - Do</th>
							<th>Zahtjev</th>
							<th>Napomena</th>
						</tr>
					</thead>
					@foreach($zahtjeviD as $zahtjevD)
						@if($zahtjevD->odobreno == '')
							<tbody>
								@if(date('Y', strtotime( $zahtjevD->GOzavršetak)) == $ova_godina)
								<tr>
									<td>
										<a class="" href="{{ route('admin.confirmation_show',[ 'vacationRequest_id' => $zahtjevD->id] ) }}"><i class="far fa-check-square"></i></a>

									</td>
									<td>{{ $zahtjevD->employee['first_name'] . ' ' . $zahtjevD->employee['last_name'] }}</td>
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
								</tr>
								@endif
							</tbody>
						@endif
					@endforeach
				</table>
			</div>
		</div>
		@endif
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
						@if($zahtjevD->odobreno != '')
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
						@endif
					@endforeach
				</table>
			</div>
		</div>
		
	@endif
	</div>
	<footer>
		<div class="ech">
			<p>Duplico je jedna od vodećih tvrtki tržišnog segmenta u domeni registriranih djelatnosti.</p>
			<p>Postigli smo to kvantitetom i kvalitetom izvedenih projekata, ponašanjem prema kupcima, kao i stavom prema poslu te učinkovitošću rada na svim dosadašnjim projektima. Upravo kroz navedene kategorije se nameće i potreba za vrednovanjem Tvog rada:</p>
			<dl class="dl_list">
				<dt>Kvantiteta</dt>
				<dd> - količina obavljenog posla, raspoloživo vrijeme za zadatke, uloženi napor, brzina rada </dd><br>
				<dt>Kvaliteta</dt>
				<dd> - znanje i sposobnost, spretnost, poštivanje rokova, pouzdanost, briga o izvršenju, fleksibilnost </dd><br>
				<dt>Ponašanje</dt>
				<dd> - sklonost timskom radu, sposobnost jasnog komuniciranja korektnost i principijelnost</dd><br>
				<dt>Stav prema poslu</dt>
				<dd> - identifikacija sa zadatkom, uloženi napor i izdržljivost, motiviranost, nezavisnost</dd><br>
				<dt>Učinkovitost</dt>
				<dd> - kontrola troška, ekonomično ponašanje, poduzetničko razmišljanje i djelovanje </dd><br>
				<dt>Donošenje odluka</dt>
				<dd> - spremnost prihvaćanja odgovornosti, sposobnost procjenjivanja, efikasnost i dosljednost u donošenju odluka, asertivnost</dd><br>
			</dl>
			Tijekom 2019. kroz sustav praćenja rada u ERP-u  doći ćemo do podatka o efektima Tvog angažmana kroz navedene kategorije, s ciljem stvaranja zajedničkog temelja našeg budućeg odnosa.
		</div>
	</footer>
@else
	<div class="jumbotron">
		<h1>Welcome, Guest!</h1>
		<p>You must login to continue.</p>
		<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">Log In</a></p>
	</div>
@endif

@stop
