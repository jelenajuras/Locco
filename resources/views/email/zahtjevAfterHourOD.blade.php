<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		
	</head>
<style>
body { 
	font-family: DejaVu Sans, sans-serif;
	font-size: 10px;
	max-width:500px;
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
	color:white;
	font-weight:bold;
	font-size:14px;
	margin:15px;
	float:left;
}

.marg_20 {
	margin:20px 0;
}
</style>
	<body>
		<h4><!--Zahtjev za potvrdu rada van radnog vremena za
			{{ date("d.m.Y", strtotime($afterHour->datum)) . ' od ' . $afterHour->vrijeme_od . ' do ' . $afterHour->vrijeme_do }}-->
				Zaprimili smo zahtjev!
			
		</h4>
		<br/> 
		<!--<div><b>{{ $odobrenje }}</b></div>
		<div><b>{{ $razlog }}</b></div>-->
	</body>
</html>
