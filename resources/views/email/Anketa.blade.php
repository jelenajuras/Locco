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
	.content {
		width:auto;
		height: auto;
		margin:auto;
		text-align:left;
		font-size:14px;
	}
	</style>
	<body>
		<div class="content">
		<p>{{ $registration->employee['first_name'] }}, do sada si poslao {{ $brojAnketa }} od minimalno 15 anketa. Preostalo za ocijeniti minimalno {{ 15 - $brojAnketa }} anketa.</p>
		</div>
	</body>
</html>