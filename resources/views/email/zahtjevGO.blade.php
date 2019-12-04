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
		<h4>Ja, {{ $employee->first_name . ' ' . $employee->last_name }}</h4>
		@if($vacationRequest->zahtjev == "GO" || $vacationRequest->zahtjev == "PL" || $vacationRequest->zahtjev == "NPL" || $vacationRequest->zahtjev == "SLD" || $vacationRequest->zahtjev == "VIK" )
			<h4>molim da mi se odobri {{ $zahtjev2 }} za
			{{ date("d.m.Y", strtotime($vacationRequest->GOpocetak)) . ' do ' . date("d.m.Y", strtotime( $vacationRequest->GOzavršetak)) . ' - ' . $dani_zahtjev . ' dana' }} </h4>
		@elseif($vacationRequest->zahtjev == "Bolovanje")
			<h4>prijavljujem bolovanje za
			{{ date("d.m.Y", strtotime($vacationRequest->GOpocetak)) . ' do ' . date("d.m.Y", strtotime( $vacationRequest->GOzavršetak)) . ' - ' . $dani_zahtjev . ' dana' }} </h4>
		@elseif($vacationRequest->zahtjev == "Izlazak")
			<h4>molim da mi se odobri {{ $zahtjev2 }} za
			{{ date("d.m.Y", strtotime($vacationRequest->GOpocetak)) . ' od ' . $vrijeme  }}</h4>
		@endif

		<div><b>Napomena: </b></div>
		<div class="marg_20">
			{{ $vacationRequest->napomena }}
			@if($vacationRequest->zahtjev == "GO")
				<p>Neiskorišteno {{ $dani_GO }} dana godišnjeg odmora </p>
			@endif
			@if($vacationRequest->zahtjev == "SLD")
				<p>Neiskorišteno {{ $slobodni_dani - $koristeni_slobodni_dani }} slobodnih dana  </p>
			@endif
		</div>		
		@if($vacationRequest->zahtjev != "Bolovanje")
			<form name="contactform" method="get" target="_blank" action="{{ route('admin.confirmation') }}">
				<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="razlog" value=""><br>
				<input type="hidden" name="id" value="{{$vacationRequest->id}}"><br>
				<input type="radio" name="odobreno" value="DA" checked> Odobreno
				<input type="radio" name="odobreno" value="NE" style="padding-left:20px;"> Nije odobreno<br>
				<input type="hidden" name="email" value="DA" checked><br>
			<!--	<input type="hidden" name="datum_odobrenja" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"><br>-->

				<input class="odobri" type="submit" value="Pošalji">

			</form>
			@endif
	</body>
</html>
