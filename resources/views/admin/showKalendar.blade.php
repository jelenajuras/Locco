@extends('layouts.admin')

@section('title', 'Raspored')
<link rel="stylesheet" href="{{ URL::asset('css/raspored.css') }}" type="text/css">
@section('content')
<div class="">
	 <div class='btn-toolbar'>
		<a class="btn btn-md" href="{{ route('home') }}">
			<i class="fas fa-home"></i>
			
		</a>
		<a class="btn btn-md" href="{{ url()->previous() }}">
			<i class="fas fa-angle-double-left"></i>
			Natrag
		</a>
	</div>
	<div class='btn-toolbar pull-right' title="Unesi sobu" >
		<a class="btn btn-lg" href="{{ route('admin.meeting_rooms.create') }}">
			<i class="far fa-building"></i>
		</a>
    </div>
	<div class='btn-toolbar pull-right' title="Unesi sastanak">
            <a class="btn btn-lg" href="{{ route('admin.meetings.create') }}" >
                <i class="fas fa-handshake" ></i>
            </a>
    </div>
	<h1>Kalendar sastanaka {{ $mjesec . '-' . $godina }}</h1>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="table-responsive">
					<table id="table_id" class="display" style="width: 100%;">
						<thead>	
							<tr>
								<th class="ime">Soba</th>
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
							@foreach($rooms as $room)
								<tr>
									<td>{{ $room->name }}</td>
									<?php 

									?>
									@foreach($list as $value2)
									<?php $dan2 = date('j', strtotime($value2)); ?>
										<td>
										@for($time = 06;$time <= 18;$time++)
											<?php 
												if(strlen($time) == 1){
													$time = '0'.$time;}
											?>
											@foreach($meetings as $meeting)
													<?php $dan3 = date('j', strtotime($meeting->datum));
														  $mjesec2 = date('m', strtotime($meeting->datum));
													?>
													@if($meeting->meeting_room_id == $room->id && $dan3 == $dan2 && $mjesec2 == $mjesec)
														
														@if($meeting->start_time == $time . ':00:00')
															<p><small>{{ $time . ':00' }}</small>
															{{ $meeting->employee['last_name']  }}
															</p>
														@endif
													@endif
											@endforeach
										@endfor

										</td>
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