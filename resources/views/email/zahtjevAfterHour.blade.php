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
	.time {
		position: relative;
		margin-bottom: 10px;
	}
	.time label {
		margin-right: 15px;
	}
	.time input {
		padding-left: 10px;
		height: 34px;
		width: auto;
		border-radius: 5px;
	}
	.time i {
		left: 10px;
		bottom: 10px;
		position: absolute;
		padding-left: 15px;
	}
	</style>
	<body>
		<h4>Ja, {{ $afterHour->employee['first_name'] . ' ' . $afterHour->employee['last_name'] }}</h4>
		
		<h4>molim da mi se potvrdi izvršeni prekovremeni rad za
		{{ date("d.m.Y", strtotime($afterHour->datum)) . ' od ' . $vrijeme  }}</h4>

		<div><b>Napomena: </b></div>
		<div class="marg_20">
			{{ $afterHour->napomena }}
		</div>		
		<form name="contactform" method="get" target="_blank" action="{{ route('admin.confirmationAfter') }}">
			<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="razlog" value=""><br>
			<input type="hidden" name="id" value="{{$afterHour->id}}"><br>
			<div class="time">
				<label>Odobreno prekovremenih sati:</label>
				<input name="odobreno_h" class="date form-control" type="time" value="{!! isset( $interval ) ? $interval : '00:00' !!}" id="date1" required><i class="far fa-clock"></i></i>
			</div>
			<input type="radio" name="odobreno" value="DA" checked> Potvrđeno
			<input type="radio" name="odobreno" value="NE" style="padding-left:20px;"> Nije potvrđeno<br>
			{{ csrf_field() }}
			<input class="odobri" type="submit" value="Pošalji">
		</form>
	</body>
</html>
