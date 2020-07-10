@extends('layouts.index')

@section('title', 'Raspored')
<link rel="stylesheet" href="{{ URL::asset('css/raspored.css') }}" type="text/css">
@section('content')
<div class="">
	 <div class='btn-toolbar'>
		<a class="btn btn-md" href="{{ url()->previous() }}">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			Natrag
		</a>
	</div>
	<h1>Raspored izostanaka {{ $mjesec . '-' . $godina }}</h1>
	
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="table-responsive">
					<table id="table_id" class="display" style="width: 100%;">
						<thead>	
							<tr>
								<th class="ime">Prezime i ime</th>
								@foreach($list as $value)
								<?php 
								$dan1 = date('D', strtotime($value));
								
								switch ($dan1) {
									 case 'Mon':
										$dan = 'P';
										break;
									case 'Tue':
										$dan = 'U';
										break;
									case 'Wed':
										$dan = 'S';
										break;
									case 'Thu':
										$dan = 'Č';
										break;
									case 'Fri':
										$dan = 'P';
										break;
									case 'Sat':
										$dan = 'S';
										break;	
									case 'Sun':
										$dan = 'N';
										break;	
								 }
								?>
									<th >{{ date('d', strtotime($value)) .' '. $dan }}</th>
								@endforeach
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach($employees as $djelatnik)
							<?php 
								$zahtjev="";
							?>
								<tr>
									<td>{{ $djelatnik->employee['last_name'] . ' ' . $djelatnik->employee['first_name'] }}</td>
									@foreach($list as $value2)
										<?php $dan2 = date('j', strtotime($value2)); ?>
										<td>
										@foreach($requests as $request)
											@if($request->employee_id == $djelatnik->employee_id)
												<?php 
													$begin = new DateTime($request['start_date']);
													$end = new DateTime($request['end_date']);
													$end->setTime(0,0,1);
													$interval = DateInterval::createFromDateString('1 day');
													$period = new DatePeriod($begin, $interval, $end);
													foreach($period as $dan3){
														if(date_format($dan3,'j') == $dan2 && date_format($dan3,'n') == $mjesec){
															$zahtjev = $request->zahtjev;
															switch ($zahtjev) {
															 case 'Izlazak':
																$zahtjev = 'IZL';
																break;
															case 'Bolovanje':
																$zahtjev = 'BOL';
																break;
															case 'COVID-19':
																$zahtjev = 'COV';
																break;
															case 'RD':
																$zahtjev = 'RD';
																break;
															case 'GO':
																$zahtjev = 'GO';
																break;
															case 'SLD':
																$zahtjev = 'SLD';
																break;
														 }
														}
													}
												?>
											@endif
										@endforeach
										{{ $zahtjev }}
										
										</td>
										<?php 
											$zahtjev="";
										?>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>	
			</div>
		</div>
					
	
	
</div>

@stop