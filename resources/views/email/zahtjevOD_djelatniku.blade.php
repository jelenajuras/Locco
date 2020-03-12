<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		
	</head>
<style>
body { 
	font-family: DejaVu Sans, sans-serif;
    font-size: 10px;
    max-width: 500px;
    padding: 20px;
    height: auto;
    overflow: hidden;
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
	color: black;
	font-weight:bold;
	font-size:14px;
	margin:15px;
	float:left;
}

.marg_20 {
	margin:20px 0;
}
</style>
	<body>
		@if($vacationRequest->zahtjev == "VIK")
			<h4>Primljeni zahtjev djelatnika  {{ $employee->employee['first_name'] . ' ' . $employee->employee['last_name'] }} </h4>
		@else
			<h4>Zahtjev djelatnika {{ $employee->employee['first_name'] . ' ' . $employee->employee['last_name'] }} za {{ $zahtjev2 }} za
			@if($vacationRequest->zahtjev != "Izlazak")
				{{ date("d.m.Y", strtotime($vacationRequest->GOpocetak)) . ' do ' . date("d.m.Y", strtotime($vacationRequest->GOzavršetak)) }} 
			@elseif($vacationRequest->zahtjev == "Izlazak")
				{{ date("d.m.Y", strtotime($vacationRequest->GOpocetak)) . ' od ' . $vacationRequest->vrijeme_od . ' do ' . $vacationRequest->vrijeme_do }}</h4>
			@endif
		@endif
		
		<br/> 
		<div><b>{{ $odobrenje }}</b></div>
		<div class="marg_20"><b>{{ $razlog }}</b></div>
		<div><b class="marg_20">{{ 'Odluku donio: ' . $odobrio }}</b></div>
	</body>
</html>
