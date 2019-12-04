@extends('layouts.admin')

@section('title', 'Raspored')
<link rel="stylesheet" href="{{ URL::asset('css/raspored.css') }}" type="text/css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@section('content')
<div class="raspored">
    <div class="page-header">
        <h1>Kalendar evidencije rada i izostanaka</h1>
		
		<div class="index_main shedule">
		<div class="shaduler_box f_left shadow_radius">
			<form accept-charset="UTF-8" role="form" class="form" method="get" action="{{ route('admin.shedulers.create') }}" autocomplete="off">
				<p>Odaberi mjesec</p>
			<span class="calendar"><input class="date date-own form-control" type="date" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" name="mjesec" id="mjesec"  ><i class="far fa-calendar-alt"></i></span>
				<input class="btn-submit"  type="submit" value="Izvještaj" id="submit"  />
			</form>
		</div>
		<div class="shaduler_box f_left shadow_radius">
			<h2>Svi zahtjevi za mjesec</h2>
			<form accept-charset="UTF-8" role="form" class="form" method="get" action="{{ route('admin.AllVacationRequest') }}" autocomplete="off">
				<p>Odaberi mjesec</p>
				<span class="calendar"><input class="date date-own form-control"  type="date" name="mjesec" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" id="mjesec3" ><i class="far fa-calendar-alt"></i></span>
				<input class="btn-submit"  type="submit" id="submit3" value="Izvještaj"  />
				
			</form>
		</div>
		<div class="shaduler_box f_left shadow_radius">
			<h2>Evidencija </h2>
			<form accept-charset="UTF-8" role="form" class="form" method="get" action="{{ route('admin.shedule') }}" autocomplete="off">
				<p>Odaberi mjesec</p>
				<span class="calendar"><input class="date date-own form-control" type="date" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" name="mjesec" id="mjesec2" ><i class="far fa-calendar-alt"></i></span>
				<input class="btn-submit" type="submit" id="submit2" value="Izvještaj"  />
			</form>
		</div>
	</div>
    </div>
</div>
 <script>
</script>
<script>
$( "#mjesec" ).change(function() {
	$("#submit").removeAttr("disabled");
});
$( "#mjesec3" ).change(function() {
	$("#submit3").removeAttr("disabled");
});
$( "#mjesec2" ).change(function() {
	$("#submit2").removeAttr("disabled");
});
</script>

@stop
