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
</style>
	<body>

		<h4>S danom {{date("d.m.Y", strtotime($djelatnik->datum_prijave)) }} zaposlen je radnik {{ $ime . ' ' . $prezime }} na radnom mjestu {{ $radno_mj }}</h4>
		<br/>
		<div><b>Napomena: </b></div>
		<div>
			{{ $napomena }}
		</div>
	
	</body>
</html>