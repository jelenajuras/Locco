<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
	</head>
	<style>
	body { 
		font-family: DejaVu Sans, sans-serif;
		font-size: 10px;
		max-width:500px;
	}
	.odobri{
		width:150px;
		height:40px;
		background-color:white;
		border: 1px solid rgb(0, 102, 255);
		border-radius: 5px;
		box-shadow: 5px 5px 8px #888888;
		text-align:center;
		padding:10px;
		color:black;
		font-weight:bold;
		font-size:14px;
		margin:15px;
		float:left;
		custor:pointer
	}
	.marg_20 {
		margin-bottom:20px;
	}
	</style>
	<body>
		<h4>Djelatnik, {{ $employee->first_name . ' ' . $employee->last_name }}</h4>
		
		<h4>poslao je zahtjev za {{ $zahtjev2 }} za
		@if($vacationRequest->zahtjev == "GO" || $vacationRequest->zahtjev == "Bolovanje" || $vacationRequest->zahtjev == "NPL" || $vacationRequest->zahtjev == "PL" || $vacationRequest->zahtjev == "COVID-19"|| $vacationRequest->zahtjev == "RD"  )
			{{ date("d.m.Y", strtotime($vacationRequest->start_date)) . ' do ' . date("d.m.Y", strtotime( $end_date)) . ' - ' . $dani_zahtjev . ' dana' }} 
		@elseif($vacationRequest->zahtjev == "Izlazak")
			{{ date("d.m.Y", strtotime($vacationRequest->start_date)) . ' od ' . $vrijeme  }}</h4>
		@endif

		<div><b>Napomena: </b></div>
		<div class="marg_20">
			{{ $vacationRequest->napomena }}
			@if($vacationRequest->zahtjev == "GO")
				<p>Neiskorišteno {{ $dani_GO }} dana godišnjeg odmora </p>
			@endif
		</div>		
		
	</body>
</html>
