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
		<h3>Djelatnik {!! isset($employee) ? $employee->first_name . ' ' .  $employee->last_name : '' !!} je poslao <a href="{{ $poruka }}" >poruku</a></h3>

		<div>
			
		</div>
	</body>
</html>