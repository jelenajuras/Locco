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
	color:white;
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

		<h4>Zahtjev za {{ $zahtjev2 }} za djelatnika {{ $ime }}
		@if($vacationRequest->zahtjev == "GO" || $vacationRequest->zahtjev == "Bolovanje" || $vacationRequest->zahtjev == "NPL" || $vacationRequest->zahtjev == "SLD")
			{{ 'od ' . date("d.m.Y", strtotime($vacationRequest->GOpocetak)) . ' do ' . date("d.m.Y", strtotime($vacationRequest->GOzavršetak)) }} 
		@elseif($vacationRequest->zahtjev == "Izlazak")
			{{ 'za dan ' . date("d.m.Y", strtotime($vacationRequest->GOpocetak)) . ' od ' . $vacationRequest->vrijeme_od . ' do ' . $vacationRequest->vrijeme_do }}</h4>
		@endif
		<br/> 
		<div><b>{{ $odobrenje }}</b></div>
		<div><b>{{ $razlog }}</b></div>
		<div><b>{{ 'Odobrio: ' . $odobrio }}</b></div>
	</body>
</html>
