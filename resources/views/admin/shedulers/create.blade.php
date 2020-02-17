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
								@if( (! $djelatnik->datum_odjave) ||
									 (date('m-Y', strtotime($djelatnik->datum_odjave )) == date('m-Y',strtotime($value))) ||
									 (date('Y', strtotime($djelatnik->datum_odjave )) >= date('Y',strtotime($value)) && date('m', strtotime($djelatnik->datum_odjave )) >= date('m',strtotime($value))) ||
									 (date('m', strtotime($djelatnik->datum_odjave )) == 1 && date('m',strtotime($value)) == 12 ))
								<?php 
									$zahtjev="";
									$redovan_Rad = '8:00';
									
									$datetime1 = date_create($djelatnik->datum_prijave); // razlika od datuma prijave
									$datetime2 = date_create($value);
									$interval = date_diff($datetime1, $datetime2);
								
								?>
									@if($interval->format('%R%a days') >= 0)
									<tr>
										<td>{{ $djelatnik->employee['last_name'] . ' ' . $djelatnik->employee['first_name'] }}
										</td>
										@foreach($list as $value2)
											<?php $dan2 = date('j', strtotime($value2)); ?>
											
											<td class="td_izostanak">
											@if(date('N',strtotime($value2)) < 6)
												@foreach($requests as $request)
													@if($request->employee_id == $djelatnik->employee_id)
														<?php 
															$begin = new DateTime($request['GOpocetak']);
															$end = new DateTime($request['GOzavršetak']);
															$end->setTime(0,0,1);
															$interval = DateInterval::createFromDateString('1 day');
															$period = new DatePeriod($begin, $interval, $end);
															foreach($period as $dan3){
																if(date_format($dan3,'j') == $dan2 && date_format($dan3,'n') == $mjesec && date_format($dan3,'Y') == $godina){
																	if(date_format($dan3,'d') == '01' & date_format($dan3,'m') == '01' ||
																		date_format($dan3,'d') == '06' & date_format($dan3,'m') == '01' ||
																		date_format($dan3,'d') == '01' & date_format($dan3,'m') == '05' ||
																		date_format($dan3,'d') == '22' & date_format($dan3,'m') == '06' ||
																		date_format($dan3,'d') == '25' & date_format($dan3,'m') == '06' ||
																		date_format($dan3,'d') == '15' & date_format($dan3,'m') == '08' ||
																		date_format($dan3,'d') == '05' & date_format($dan3,'m') == '08' ||
																		date_format($dan3,'d') == '08' & date_format($dan3,'m') == '10' ||
																		date_format($dan3,'d') == '01' & date_format($dan3,'m') == '11' ||
																		date_format($dan3,'d') == '25' & date_format($dan3,'m') == '12' ||
																		date_format($dan3,'d') == '26' & date_format($dan3,'m') == '12' ||date_format($dan3,'d') == '02' & date_format($dan3,'m') == '04' & date_format($dan3,'Y') == '2018' ||
																		date_format($dan3,'d') == '31' & date_format($dan3,'m') == '05' & date_format($dan3,'Y') == '2018' ||
																		date_format($dan3,'d') == '22' & date_format($dan3,'m') == '04' & date_format($dan3,'Y') == '2019' ||
																		date_format($dan3,'d') == '20' & date_format($dan3,'m') == '06' & date_format($dan3,'Y') == '2019' ||
																		date_format($dan3,'d') == '13' & date_format($dan3,'m') == '04' & date_format($dan3,'Y') == '2020' ||
																		date_format($dan3,'d') == '11' & date_format($dan3,'m') == '06' & date_format($dan3,'Y') == '2020'){
																			
																		}else{
																			$zahtjev = $request->zahtjev;
																				switch ($zahtjev) {
																				 case 'Izlazak':
																					$zahtjev = 'IZL';
																					break;
																				case 'Bolovanje':
																					$zahtjev = 'BOL';
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
															}
														?>
													@endif
												@endforeach
												@if($zahtjev)
													<span class="izostanak1">{{ $zahtjev }}<br>{{ ' 8:00' }}</span>
												@else
													@if(strtotime($value2) >= strtotime($djelatnik->datum_prijave))
														@if($djelatnik->datum_odjave == null || strtotime($value2) <= strtotime($djelatnik->datum_odjave))
															<span class="izostanak2">{{ $redovan_Rad  }}</span>
														@endif
													@endif
												@endif
											@endif
											
											</td>
											
											<?php 
												$zahtjev="";
											?>
										@endforeach
									</tr>
									@endif
								@endif
							@endforeach
						</tbody>
					</table>
				</div>	
			</div>
		</div>
</div>
@stop