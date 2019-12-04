<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
	</head>
	<style>
	body { 
		font-family: DejaVu Sans, sans-serif;
		font-size: 10px;
	}
	.red{
		color:red;
		font-weight:bold;
	}
	</style>
	<body>
		<h3>Izostanci za dan: {{ $datum }} </h3>
		@foreach($dan_izostanci as $djelatnik)
			<div>
				{{ $djelatnik['zahtjev'] . ', ' . $djelatnik['ime'] . ', ' . (string)$djelatnik['period'] }} 
					@if($djelatnik['zahtjev'] == 'Izostanak' || $djelatnik['zahtjev'] == 'Izlazak')
					{{', ' . $djelatnik['vrijeme'] }}
					@endif
			</div>
		@endforeach
	</body>
</html>