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
	
	?>
	<body>
		<h3>Djelatnik {{  $employee->first_name . ' ' .  $employee->last_name }} je poslao komentar na radnu uputu <a href="{{ $link }}">{{ $instruction->title }}</a></h3>
	</body>
</html>