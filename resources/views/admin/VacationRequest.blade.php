<!DOCTYPE html>
<html>
<header>
	<title>Zahtjev {{$vacationRequest->employee['last_name'] }}</title>
	<link rel="stylesheet" href="{{ URL::asset('css/vacation_req.css') }}"/>
</header>
<body>


<div class="container">
	<div class="zahtjevPrint">
		<img src="{{ asset('img/Logo_Duplico.png')}}"/>
		<header>
			<h2><span>Zahtjev</span><br>
			za {{ $zahtjev }} </h2>
		</header>
		<main>
		<p>Ja {{ $vacationRequest->employee['first_name'] . ' ' . $vacationRequest->employee['last_name'] }} molim da mi se odobri {{ $zahtjev }}</p>
		<p>u periodu od {{ date('d.m.Y', strtotime( $vacationRequest->GOpocetak )) }} do  {{date('d.m.Y', strtotime( $vacationRequest->GOzavršetak ))   }} u trajanju od {{ $daniGO }} radnih dana.</p>
		</main>
		<footer>
			<div class="datum">
				<p>{{date('d.m.Y', strtotime( $vacationRequest->created_at))  }} </p>
				<span><small>(Datum podnošenja zahtjeva)</small></span>
			</div>
			
			<p class="potpis">Potpis radnika:<span class="potpisCrta"></span></p>
			<div class="odobrio">
				<p>Zahtjev odobrio: {{ $vacationRequest->authorized['first_name'] . ' ' . $vacationRequest->authorized['last_name'] }}</p>
				<p>dana: {{ date('d.m.Y', strtotime( $vacationRequest->datum_odobrenja )) }} </p>
			</div>
		</footer>
	</div>
</div>
</body>
</html>
