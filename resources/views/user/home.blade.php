@extends('layouts.admin')

@section('title', 'Naslovnica')
@php
	if(isset($_GET['dan'])) {
		$select_day = $_GET['dan'];
	} else {
		$select_day = date('Y-m-d');
	}
@endphp
@section('content')

@if(Sentinel::check())
	@if(isset($dataArr))
	<section class="calendar col-sm-12 col-md-12 col-lg-12">
		<div hidden class="dataArr">{!! json_encode($dataArr) !!}</div>
		
		<div class="calender_view col-sm-12 col-md-12 col-lg-6"></div>
		
		<div class="all_events col-md-6">
			@if(isset($select_day))
				<h4>Događaji na dan {{ date("d.m.Y",strtotime($select_day)) }}</h4>
				@foreach($dataArr as $key => $data)
					<div class="show_event" >
						<div class="event">
							@if($data['name'] == 'birthday')
								@if(date("m-d",strtotime($data['date'])) == date("m-d",strtotime($select_day)) )
									<p>{{ 'Rođendan - ' . $data['employee'] }}</p>
								@endif
							@endif
							@if($data['name'] == 'absence')
								@if(date("m-d-Y",strtotime($data['date'])) == date("m-d-Y",strtotime($select_day)) )
									@if(($data['type'] == 'Izlazak'))
										<p>{{ isset($data['employee']) ? $data['type'] . ' - ' . $data['employee'] . ' (' . $data['time'] . ')'  : '' }}</p>
									@else
										<p>{{ isset($data['employee']) ? $data['type'] . ' - ' . $data['employee'] : '' }}</p>
									@endif
								@endif
							@endif
							@if($data['name'] == 'LP')
								@if(date("m-d-Y",strtotime($data['date'])) == date("m-d-Y",strtotime($select_day)) )
									<p>{{ isset($data['employee']) ? 'Liječnički pregled' . ' - ' . $data['employee'] : '' }}</p>
								@endif
							@endif
							@if($data['name'] == 'event')
								@if(date("m-d-Y",strtotime($data['date'])) == date("m-d-Y",strtotime($select_day)) )
									<p>{{   $data['type'] . ' - ' . $data['title'] . ' - ' . $data['time']  }}</p>
								@endif
							@endif
						</div>
					</div>
				@endforeach
			@endif
			@if (Sentinel::inRole('administrator') || Sentinel::inRole('uprava'))<a class="add_event" href="{{ route('admin.events.create') }}" ><span>Dodaj događaj</span></a>@endif
		</div>
	</section>
	@endif
	<main class="col-sm-12 col-md-12 col-lg-12">
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
						<a class="" href="{{ route('admin.noticeBoard') }}" ><span>Oglasna ploča</span></a>
					</div>
				</div>
				<div class="BTNbox">
					<div class="dashboard_box2 najava">
						<a class="" href="{{ route('admin.announcement') }}" ><span>Najava aktivnosti</span></a>
					</div>
				</div>
				@if(isset($employee))
					<div class="BTNbox">
						<div class="dashboard_box2 benefits">
							<a class="" href="{{ route('admin.benefits.show', 1) }}"  ><span>Pogodnosti za zaposlenike</span></a>
						</div>
					</div>
				@endif
				<div class="BTNbox">
					<div class="dashboard_box2 benefits">
						<a class="" href="{{ route('admin.show_instructions') }}"  ><span>Radne upute</span></a>
					</div>
				</div>
				<div class="BTNbox">
					<div class="dashboard_box2 oglasnik">
						<a href="{{ route('admin.oglasnik') }}">
							<span>Naše Njuškalo</span></a>
					</div>
				</div>
			</div>
			@if(isset($reg_employee))
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
							<a href="{{ route('admin.registrations.show', $reg_employee) }}">
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
					@if (Sentinel::inRole('administrator') )
						<div class="BTNbox">
							<div class="dashboard_box2">
								<a href="{{ route('admin.catalog_categories.show',1) }}">
									<span>Važne informacije za montere</span></a>
							</div>
						</div>
					@endif
					@if(count($questionnaires))
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
					@endif
					@if(count($educations))
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
					@endif
					<div class="BTNbox">
						<div class="dashboard_box2 prezentacije">
							<a href="{{ route('admin.presentations.show', '0') }}">
								<span>Edukacijske prezentacije</span></a>
						</div>
					</div>
				</div>
			@endif
		</div>		
		@if(Sentinel::inRole('administrator'))
			<div class="row">
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
														<form name="contactform" class="conf_form" method="get" action="{{ route('admin.confDirectorAfter') }}">
															<input type="hidden" name="id" value="{{ $afterHour->id}}"><br>
															<input type="radio" hidden name="odobreno" value="DA" checked>
															<input class="odobri" type="submit" value="&#10004;">
														</form>
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
				@if(count($zahtjevi_neodobreni) > 0)
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
										<th>Odobrenje<br>voditelja</th>
										<th>Nadređena<br>osoba</th>
										<th>Napomena</th>
									</tr>
								</thead>
								<tbody>
									@foreach($zahtjevi_neodobreni as $zahtjev1)
										@if(date('Y', strtotime( $zahtjev1->GOzavršetak)) >= $ova_godina)
											<tr>
												<td>
													<form name="contactform" class="conf_form" method="get" action="{{ route('admin.confDirector') }}">
														<input type="hidden" name="id" value="{{ $zahtjev1->id}}"><br>
														<input type="radio" hidden name="odobreno" value="DA" checked>
														<input class="odobri" type="submit" value="&#10004;">
													</form>
												<!--	<a class="" href="{{ route('admin.confirmation_show',[ 'id' => $zahtjev1->id] ) }}"><i class="far fa-check-square"></i></a>-->
												</td>
												<td>{{ $zahtjev1->employee['first_name'] . ' ' . $zahtjev1->employee['last_name'] }}</td>
												<td>{{ date('d.m.Y.', strtotime( $zahtjev1->GOpocetak)) }}<br>
												@if($zahtjev1->GOzavršetak != $zahtjev1->GOpocetak ){{ date('d.m.Y.', strtotime( $zahtjev1->GOzavršetak)) }}
												@elseif( $zahtjev1->zahtjev != 'GO')
												{{ date('H:i', strtotime( $zahtjev1->GOpocetak. ' '. $zahtjev1->vrijeme_od))  }} - {{  date('H:i', strtotime( $zahtjev1->GOpocetak. ' '. $zahtjev1->vrijeme_do)) }}
												@endif
												</td>
												<?php 
													$brojDana =1;
													$begin = new DateTime($zahtjev1->GOpocetak);
													$end = new DateTime($zahtjev1->GOzavršetak);
													$interval = DateInterval::createFromDateString('1 day');
													$period = new DatePeriod($begin, $interval, $end);
													foreach ($period as $dan) {
														if(date_format($dan,'N') < 6){
															$brojDana += 1;
														}
													}
												?>
												<td>{{ $zahtjev1->zahtjev }}
													@if($zahtjev1->zahtjev == 'GO') 
														{{ $brojDana . ' dana ' }}
													@endif
												</td>
												<td>
													@if ($zahtjev1->odobreno2 != null && $zahtjev1->odobreno2 != '' )
													{{ $zahtjev1->odobreno2 }}
													@endif
												</td>
												<td>
													@if (isset($zahtjev1->employee->superior) && $zahtjev1->employee->superior != null && $zahtjev1->employee->superior != '')
														{{ $zahtjev1->employee->superior['first_name'] . ' ' . $zahtjev1->employee->superior['last_name'] }}<br>
													@endif
													@if (isset($zahtjev1->employee->work->prvi_nadredjeni) && $zahtjev1->employee->work->prvi_nadredjeni != null && $zahtjev1->employee->work->prvi_nadredjeni != '')
														{{ $zahtjev1->employee->work->prvi_nadredjeni['first_name'] . ' ' . $zahtjev1->employee->work->prvi_nadredjeni['last_name'] }}<br>
													@endif
													@if (isset($zahtjev1->employee->work->nadredjeni) && $zahtjev1->employee->work->nadredjeni != null && $zahtjev1->employee->work->nadredjeni != '')
														{{ $zahtjev1->employee->work->nadredjeni['first_name'] . ' ' . $zahtjev1->employee->work->nadredjeni['last_name'] }}
													@endif
												</td>
												<td>{{ $zahtjev1->napomena }}</td>
											</tr>
											@endif
									@endforeach
								</tbody>
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
							@foreach($zahtjevi_odobreni as $zahtjev)
								<tbody>
									@if(date('Y', strtotime( $zahtjev->GOzavršetak)) == $ova_godina)
									<tr><td>{{ $zahtjev->employee['first_name'] . ' ' . $zahtjev->employee['last_name'] }}</td>
										<td>{{ date('d.m.Y.', strtotime( $zahtjev->GOpocetak)) }}<br>
										@if($zahtjev->GOzavršetak != $zahtjev->GOpocetak ){{ date('d.m.Y.', strtotime( $zahtjev->GOzavršetak)) }}
										@elseif( $zahtjev->zahtjev != 'GO')
										{{ date('H:i', strtotime( $zahtjev->GOpocetak. ' '. $zahtjev->vrijeme_od))  }} - {{  date('H:i', strtotime( $zahtjev->GOpocetak. ' '. $zahtjev->vrijeme_do)) }}
										@endif
										</td>
										<?php 
											$brojDana =1;
											$begin = new DateTime($zahtjev->GOpocetak);
											$end = new DateTime($zahtjev->GOzavršetak);
											$interval = DateInterval::createFromDateString('1 day');
											$period = new DatePeriod($begin, $interval, $end);
											foreach ($period as $dan) {
												if(date_format($dan,'N') < 6){
													$brojDana += 1;
												}
											}
										?>
										<td>{{ $zahtjev->zahtjev }}
											@if($zahtjev->zahtjev == 'GO') 
												{{ $brojDana . ' dana ' }}
											@endif
										</td>
										<td>{{ $zahtjev->napomena }}</td>
										<td>{{ $zahtjev->odobreno }} {{ $zahtjev->razlog  }}</td>
									</tr>
									@endif
								</tbody>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		@endif
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
	</main>
@else
	<div class="jumbotron">
		<h1>Welcome, Guest!</h1>
		<p>You must login to continue.</p>
		<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">Log In</a></p>
	</div>
@endif
<script src="{{ asset('js/efc_toggle.js') }}"></script>
<script>
	$('.conf_form').submit(function(){
		if (!confirm("Are you sure about this change?")) {
			return false;
		}
	});

	$(function() {
		$('.ech .jumbotron h4').click(function(){
			$('.ech .jumbotron table').toggle();
		});
		$.getScript( '/../js/event.js');
	});
</script>
<script src="{{ URL::asset('node_modules/moment/moment.js') }}"></script>
<script src="{{ URL::asset('node_modules/pg-calendar/dist/js/pignose.calendar.min.js') }}"></script>
@stop
