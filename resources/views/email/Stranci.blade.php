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
	<?php 
	$date1 = new DateTime($djelatnik->datum_dozvola); 
    $date2 = new DateTime("now"); 
    $interval = $date1->diff($date2); 
    $days = $interval->format('%a');
	?>
	<body>
		<h3>Djelatniku {{ $ime . ' ' . $prezime }} dozvola za boravak ističe za  {{ $days }} dana!</h3>

		<div>
			Datum isteka dozvole za boravak: {{ date("d.m.Y", strtotime($djelatnik->datum_dozvola)) }}
		</div>
	</body>
</html>