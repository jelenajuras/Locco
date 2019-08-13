@extends('layouts.admin')

@section('title', 'Naslovnica')

@section('content')
@if(Sentinel::check())
	<div class="row">
		@if(isset($employee))
		<div class="ech">
			<p>{{ $employee->first_name . ' ' . $employee->last_name }} ukupan trošak Tvoje godišnje plaće iznosi 
			<span class="efc"><b>{{  number_format($ech['brutto'],2,",",".") . ' kn' }}</b>.</span>
			<span class="efc_show">prikaži</span>
			<span class="efc_hide">sakrij</span></p>
			<p>Efektivna cijena Tvog sata rada u Duplicu iznosi po satu: <span class="efc"><b>{{  number_format($ech['effective_cost'],2,",",".") . ' kn' }}</b></span> <span class="efc_show">prikaži</span><span class="efc_hide">sakrij</span>, a obračunata je kao stvarno provedeno vrijeme na radu kroz bruto troškove godišnje plaće, a sve za redovan rad.</p>
		</div>
		@endif
		<div class="dashboard_box1">
			<div class="BTNbox">
				<div class="dashboard_box2 oglasna">
					<a class="" href="{{ route('admin.noticeBoard') }}"  ><span>Oglasna ploča</span></a>
				</div>
			</div>
			@if(isset($employee))
			<div class="BTNbox">
				<div class="dashboard_box2 benefits">
					<a class="" href="{{ route('admin.benefits.show', 1) }}"  ><span>Pogodnosti za zaposlenike</span></a>
				</div>
			</div>
			@endif
			@if(isset($ads))
			<div class="BTNbox">
				<div class="dashboard_box2 oglasnik">
					<a href="{{ route('admin.oglasnik') }}">
						<span>Naše Njuškalo</span></a>
				</div>
			</div>
			@endif

		</div>
		@if(isset($employee))
			<div class="dashboard_box1">
			
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
				@if(isset($afterHours))
				<div class="BTNbox">
					<div class="dashboard_box2">
						@if (Sentinel::inRole('administrator'))
						<a class="" href="{{ route('admin.afterHours.index') }}"><span>Prekovremeni rad</span></a>
						@else
						<a class="" href="{{ route('admin.afterHours.create') }}"><span>Prekovremeni rad</span></a>
						@endif
					</div>
				</div>
				@endif
				<div class="BTNbox">
					<div class="dashboard_box2">
						<a href="{{ route('admin.registrations.show', $registration->id) }}">
							<span>Opći podaci<br>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</span></a>
					</div>
				</div>
				@if (Sentinel::inRole('uprava') || Sentinel::getUser()->last_name == 'Barberić')
					<div class="BTNbox">
						<div class="dashboard_box2">
							<a href="{{ route('admin.notices.create') }}">
								<span>Nova obavijest</span></a>
						</div>
					</div>
				@endif
			</div>
			<div class="dashboard_box1">
				@if($questionnaires)
					@foreach($questionnaires->where('status','aktivna') as $questionnaire)
						<div class="BTNbox">
							<div class="dashboard_box2 anketa">
								<a href="{{ route('admin.questionnaires.show', $questionnaire->id) }}">
									<span>Anketa<br> <br>{{$questionnaire->naziv}}</span></a>
							</div>
						</div>
					@endforeach
				@endif
				@if($educations)
					@foreach($educations as $education)
						<div class="BTNbox">
							<div class="dashboard_box2 edukacija">
								<a href="{{ route('admin.educations.show', $education->id) }}">
									<span>Edukacija<br><br>{{$education->name}}</span></a>
							</div>
						</div>
						@endforeach
				@endif
				@if(count($presentations))
					<div class="BTNbox">
						<div class="dashboard_box2 prezentacije">
							<a href="{{ route('admin.presentations.show', '0') }}">
								<span>Edukacijske prezentacije</span></a>
						</div>
					</div>
				@endif
			</div>
		@endif
	</div>
	<div class="row">
	@if(Sentinel::inRole('administrator'))
		@if(isset($afterHours))	
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
		@endif
		@if(isset($zahtjeviD))	
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
					@foreach($zahtjeviD->take(30) as $zahtjevD)
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
	@if(isset($employee))
	<footer>
		@foreach($questionnaires as $questionnaire)
			@if(count($evaluations->where('questionnaire_id', $questionnaire->id)) > 0)
				<div class="ech">
					<div class="jumbotron">
						<div>
							<h4>Rezultati ankete {{ $questionnaire->name }} i moji ciljevi</h4>
							<table>
								<thead>
									<tr>
										<th>Grupa</th>
										<th>Rezultat</th>
										<th>Ciljana ocjena</th>
										<th>Cilj</th>
									</tr>
								</thead>
								<tbody>
								
									@foreach($evaluatingGroups->where('questionnaire_id', $questionnaire->id) as $evaluatingGroup)
									
										<tr  class="ev_group">
											<td colspan="2">{{ $evaluatingGroup->naziv }}</td>
											@foreach($evaluationTargets->where('group_id',$evaluatingGroup->id) as $evaluationTarget)
													
											<td class="">{!! $evaluationTarget->target != 0 ? $evaluationTarget->target : '' !!}</td>
											<td class="">{{ $evaluationTarget->comment }}</td>
											@endforeach
										</tr>
										@foreach($evaluatingQuestions->where('group_id',$evaluatingGroup->id) as $evaluatingQuestion)
								
											<tr class="ev_question">
												<td>{{ $evaluatingQuestion->naziv }}</td>
												<?php 
												$rating = 0;
												$rating1 = 0;
												$i = 0;		
												foreach($evaluations->where('question_id', $evaluatingQuestion->id) as $evaluation){
													$rating += $evaluation->rating;
													$i ++;
													$rating1 = $rating / $i;
												}
												?>
												<td>{{ number_format($rating1, 2) }}</td>
												
											</tr>
										@endforeach
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			@endif
		@endforeach
	</footer>
	@endif
@else
	<div class="jumbotron">
		<h1>Welcome, Guest!</h1>
		<p>You must login to continue.</p>
		<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">Log In</a></p>
	</div>
@endif
<script src="{{ asset('js/efc_toggle.js') }}"></script>
@stop
