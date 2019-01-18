@extends('layouts.admin')

@section('title', 'Raspored')
<link rel="stylesheet" href="{{ URL::asset('css/raspored.css') }}" type="text/css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@section('content')
<div class="raspored">
    <div class="page-header">
        <h1>Kalendar evidencije rada i izostanaka</h1>
		
		<form accept-charset="UTF-8" role="form" class="form" method="get" action="{{ route('admin.shedulers.create') }}">
			<input class="date date-own form-control" type="text" name="mjesec" id="mjesec" placeholder="Izbor mjeseca" onblur="submitEnabled()"><i class="far fa-calendar-alt"></i>
			<button type="submit" value="Odaberi" id="submit" disabled>Odaberi</button>
			<script type="text/javascript">
				$('.date-own').datepicker({
					minViewMode: 1,
					format: 'm-yyyy'
				});
			</script>
		</form>
    </div>
	<div class="page-header">
        <h1>Svi zahtjevi za mjesec</h1>
		<form accept-charset="UTF-8" role="form" class="form" method="get" action="{{ route('admin.AllVacationRequest') }}">
			<input class="date date-own form-control" type="text" name="mjesec" id="mjesec3" placeholder="Izbor mjeseca"  onblur="submitEnabled()"><i class="far fa-calendar-alt"></i>
			<button type="submit" id="submit3" value="Odaberi" disabled>Odaberi</button>
			<script type="text/javascript">
				$('.date-own').datepicker({
					minViewMode: 1,
					format: 'm-yyyy'
				});
			</script>
		</form>
    </div>
    <div class="page-header">
        <h1>Evidencija </h1>
		<form accept-charset="UTF-8" role="form" class="form" method="get" action="{{ route('admin.shedule') }}">
			<input class="date date-own form-control" type="text" name="mjesec" id="mjesec2" placeholder="Izbor mjeseca"  onblur="submitEnabled()"><i class="far fa-calendar-alt"></i>
			<button type="submit" id="submit2" value="Odaberi" disabled>Odaberi</button>
			<script type="text/javascript">
				$('.date-own').datepicker({
					minViewMode: 1,
					format: 'm-yyyy'
				});
			</script>
		</form>
    </div>
</div>
<script type="text/javascript">
	$('.date-own').datepicker({
		minViewMode: 1,
		format: 'm-yyyy'
	});
</script>
<script>
function submitEnabled(){
	var input = document.getElementById("mjesec").value;
	var input2 = document.getElementById("mjesec2").value;
	var input3 = document.getElementById("mjesec3").value;
	
	if(input != ''){
		document.getElementById("submit").disabled = false;
	}
	if(input2 != ''){
		document.getElementById("submit2").disabled = false;
	}
	if(input3 != ''){
		document.getElementById("submit3").disabled = false;
	}
}
</script>
@stop
