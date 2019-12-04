@extends('layouts.admin')

@section('title', 'Naslovnica')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
@if(isset($vacationRequest) && $vacationRequest->odobreno == 'DA'  )
	<div class="odobreno">
	<h3>Zahtjev je već odobren. </h3>
	<p>Odobrio {{ $vacationRequest->authorized['first_name'] . ' ' . $vacationRequest->authorized['last_name'] }}</p>
	Status {{ $vacationRequest->odobreno }}
	@if($vacationRequest->razlog != null && $vacationRequest->razlog != '' )
		{{ $vacationRequest->razlog }}
	@endif
	</p>
	<p>Datum odobrenja {{ $vacationRequest->datum_odobrenja }}</p>

	<h4>Želiš li promjeniti odobrenje?

	<a class="btn1" id="da">DA</a>
	<a class="btn1" href="{{ route('home') }}" id="ne">NE</a></h4>
	</div>
@endif
@if($vacationRequest->odobreno == 'DA'))
	<div class="odobrenje" hidden>
@else
	<div class="odobrenje" >
@endif
	<h3>Odobrenje zahtjeva</h3>
		<form name="contactform" method="get" action="{{ route('admin.confDirector') }}">
			<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="razlog" value=""><br>
			<input type="hidden" name="id" value="{{ $vacationRequest_id}}"><br>
			<input type="radio" name="{!! isset($vacationRequest) && $vacationRequest->employee->work->nadredjeni->id == $employee->id ? 'odobreno' : 'odobreno2' !!}" value="DA" checked> Odobreno
			<input type="radio" name="{!! isset($vacationRequest) && $vacationRequest->employee->work->nadredjeni->id == $employee->id ? 'odobreno' : 'odobreno2' !!}" value="NE" style="padding-left:20px;"> Nije odobreno<br>
			<input type="hidden" name="user_id" value="{{ Sentinel::getUser()->id }}"><br>
			<input type="hidden" name="datum_odobrenja" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"><br>
			@if($vacationRequest->employee->work->nadredjeni->id == $employee->id)
				<input type="hidden" name="uprava" value="uprava" ><br>
			@endif
			<div class="form-group">
				<label for="email">Slanje emaila:</label><br>
				<input type="radio" name="email" value="DA" checked> Poslati e-mail<br>
				<input type="radio" name="email" value="NE"> Ne slati mail
			</div>
			<input class="odobri" type="submit" value="Pošalji">
		</form>
</div>
<script>
$('#da').click(function(){
	$('.odobrenje').show();
});
$('#ne').click(function(){
	$('.odobrenje').hide();
});
</script>
@stop
