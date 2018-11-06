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
		
		<h4>molim da mi se potvrdi izvršeni prekovremeni rad za
		{{ date("d.m.Y", strtotime($datum)) . ' od ' . $vrijeme  }}</h4>

		<div><b>Napomena: </b></div>
		<div class="marg_20">
			{{ $napomena }}
		</div>		
		<form name="contactform" method="get" target="_blank" action="{{ route('admin.confirmationAfter') }}">
			<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="razlog" value=""><br>
			<input type="hidden" name="id" value="{{$afterHour->id}}"><br>
			<input type="radio" name="odobreno" value="DA" checked> Potvrđeno
			<input type="radio" name="odobreno" value="NE" style="padding-left:20px;"> Nije potvrđeno<br>
			<input type="hidden" name="user_id" value="{{ $nadredjeni1 }}"><br>
			<input type="hidden" name="datum_odobrenja" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"><br>

			<input class="odobri" type="submit" value="Pošalji">


		</form>
	</body>
</html>
