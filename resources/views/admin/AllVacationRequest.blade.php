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
					$dani = GodisnjiController::daniGO(['GOpocetak' => $vacationRequest->GOpocetak, 'GOzavršetak' => $vacationRequest->GOzavršetak] );
				?>
				<tr>
					<td>{{ $vacationRequest->employee['first_name'] . ' ' . $vacationRequest->employee['last_name']}}</td>
					<td>{{ date('d.m.Y.', strtotime( $vacationRequest->GOpocetak)) }}</td>
					<td>{{ date('d.m.Y.', strtotime( $vacationRequest->GOzavršetak)) }}</td>
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
					$vrijeme = GodisnjiController::izlazak(['od' => $vacationRequest->vrijeme_od, 'do' => $vacationRequest->vrijeme_do] );
				?>
				<tr>
					<td>{{ $vacationRequest->employee['first_name'] . ' ' . $vacationRequest->employee['last_name']}}</td>
					<td>{{ date('d.m.Y.', strtotime( $vacationRequest->GOpocetak)) }}</td>
					<td>{{ date('H:i', strtotime( $vacationRequest->GOpocetak. ' '. $vacationRequest->vrijeme_od))  }} - {{  date('H:i', strtotime( $vacationRequest->GOpocetak. ' '. $vacationRequest->vrijeme_do)) }}</td>
					<td>{{ $vrijeme }}</td>
					<td>{{ $vacationRequest->napomena }}</td>
				</tr>
				@endif
		@endforeach
		</tbody>
	</table>
</div>


@stop