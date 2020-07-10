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
		@if($request->zahtjev == "SLD" || $request->zahtjev == "VIK" )
			<h4>molim da mi se odobri {{ $zahtjev2 }} za
			{{ date("d.m.Y", strtotime($request->start_date)) . ' do ' . date("d.m.Y", strtotime( $request->end_date)) . ' - ' . $dani_zahtjev . ' dana' }} </h4>
		@elseif($request->zahtjev == "Bolovanje")
			<h4>prijavljujem bolovanje za
			{{ date("d.m.Y", strtotime($request->start_date)) . ' do ' . date("d.m.Y", strtotime( $request->end_date)) . ' - ' . $dani_zahtjev . ' dana' }} </h4>
		@elseif($request->zahtjev == "Izlazak")
			<h4>molim da mi se odobri {{ $zahtjev2 }} za
			{{ date("d.m.Y", strtotime($request->start_date)) . ' od ' . $vrijeme  }}</h4>
		@endif

		<div><b>Napomena: </b></div>
		<div class="marg_20">
			{{ $request->napomena }}
		</div>		
		@if($request->zahtjev != "Bolovanje")
			<form name="contactform" method="get" target="_blank" action="{{ route('admin.confirmationTemp') }}">
				<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="razlog" value=""><br>
				<input type="hidden" name="id" value="{{ $request->id }}"><br>
				<input type="radio" name="odobreno" value="DA" checked> Odobreno
				<input type="radio" name="odobreno" value="NE" style="padding-left:20px;"> Nije odobreno<br>
				<input type="hidden" name="email" value="DA" checked><br>
		
				<input class="odobri" type="submit" value="Pošalji">

			</form>
			@endif
	</body>
</html>
