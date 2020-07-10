@extends('layouts.admin')

@section('title', 'Zahtjevi')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
<?php
	use App\Http\Controllers\GodisnjiController;
?>
@section('content')
<div class="row">
	<h2>Godišnji odmor</h2>
	
	<table id="table_id" class="display" style="width: 100%;">
		<thead>
			<tr>
				<th>Djelatnik</th>
				<th>Datum početka</th>
				<th>Datum završetka</th>
				<th>Broj dana</th>
				<th>Tip zahtjeva</th>
				<th>Napomena</th>
			</tr>
		</thead>
		<tbody id="myTable" >
		@foreach($vacationRequests as $vacationRequest)
			@if($vacationRequest->zahtjev != 'Izlazak') 
				<?php
					$dani = GodisnjiController::daniGO(['start_date' => $vacationRequest->start_date, 'end_date' => $vacationRequest->end_date] );
				?>
				<tr>
					<td>{{  $vacationRequest->employee['last_name']  . ' ' . $vacationRequest->employee['first_name']}}</td>
					<td>{{ date('d.m.Y', strtotime( $vacationRequest->start_date)) }}</td>
					<td>{{ date('d.m.Y', strtotime( $vacationRequest->end_date)) }}</td>
					<td>{{ $dani }}</td>
					<td>{{ $vacationRequest->zahtjev }}</td>
					<td class="align-left">{{ $vacationRequest->napomena }}</td>
				</tr>
			@endif
		@endforeach
		</tbody>
	</table>
</div>
<div class="row">
	<h2>Izostanci </h2>
	
	<table id="tbl_izostanci" class="display" style="width: 100%;">
		<thead>
			<tr>
				<th>Djelatnik</th>
				<th>Datum početka</th>
				<th>Vrijeme</th>
				<th>Period odsutnosti</th>
				<th>Napomena</th>
			</tr>
		</thead>
		<tbody>
		@foreach($vacationRequests as $vacationRequest)
			@if($vacationRequest->zahtjev == 'Izlazak') 
				<?php
					$vrijeme = GodisnjiController::izlazak(['od' => $vacationRequest->start_time, 'do' => $vacationRequest->end_time] );
				?>
				<tr>
					<td>{{ $vacationRequest->employee['first_name'] . ' ' . $vacationRequest->employee['last_name']}}</td>
					<td>{{ date('d.m.Y.', strtotime( $vacationRequest->start_date)) }}</td>
					<td>{{ date('H:i', strtotime( $vacationRequest->start_date. ' '. $vacationRequest->start_time))  }} - {{  date('H:i', strtotime( $vacationRequest->start_date. ' '. $vacationRequest->end_time)) }}</td>
					<td>{{ $vrijeme }}</td>
					<td>{{ $vacationRequest->napomena }}</td>
				</tr>
				@endif
		@endforeach
		</tbody>
	</table>
</div>


@stop