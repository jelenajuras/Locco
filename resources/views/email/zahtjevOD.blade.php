<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		
	</head>
<style>
body { 
	font-family: DejaVu Sans, sans-serif;
    font-size: 10px;
    width: 100%;
	max-width: 800px;
	height: auto;
    padding: 20px;  
    overflow: hidden;
	margin: auto !important;
}
.odobri {
	width: auto;
	height: 40px;
	background-color:white;
	border: 1px solid rgb(0, 102, 255);
	border-radius: 5px;
	box-shadow: 5px 5px 8px #888888;
	text-align:center;
	padding:10px 50px;
	color: black;
	font-weight: bold;
	font-size: 14px;
	margin: auto;
	margin-bottom: 10px;
    display: block;
}
</style>
	<body>
		<div>
			<h4>Zahtjev za {{ $zahtjev2 }} <br>
			za 
			@if($vacationRequest->zahtjev == "GO" || $vacationRequest->zahtjev == "Bolovanje" || $vacationRequest->zahtjev == "RD" || $vacationRequest->zahtjev == "COVID-19" || $vacationRequest->zahtjev == "PL"  || $vacationRequest->zahtjev == "NPL" || $vacationRequest->zahtjev == "SLD")
				{{ date("d.m.Y", strtotime($vacationRequest->start_date)) . ' do ' . date("d.m.Y", strtotime($vacationRequest->end_date)) }} 
			@elseif($vacationRequest->zahtjev == "Izlazak")
				{{ date("d.m.Y", strtotime($vacationRequest->start_date)) . ' od ' . $vacationRequest->start_time . ' do ' . $vacationRequest->end_time }}
			@endif
			</h4>
			<br> 
			<div><b>{{ $odobrenje }}</b></div>
			<div><b>{{ $razlog }}</b></div>
			<div><b>{{ 'Odobrio: ' . $odobrio }}</b></div>
			
			<h3>Potvrda odobrenja</h3>

			<form name="contactform" method="get" target="_blank" action="{{ route('admin.confirmation') }}">
				<input style="height: 34px; width:100%; max-width: 500px;border-radius: 5px;" type="text" name="razlog" value=""><br>
				<input type="hidden" name="id" value="{{$vacationRequest->id}}"><br>
				<input type="radio" name="odobreno2" value="DA" checked> Odobreno
				<input type="radio" name="odobreno2" value="NE" style="padding-left:20px;"> Nije odobreno<br>
				<input type="hidden" name="email" value="DA" checked><br>
				<input type="hidden" name="uprava" value="uprava"><br>
				<input class="odobri" type="submit" value="Pošalji">
			</form>
		</div>
	</body>
</html>
