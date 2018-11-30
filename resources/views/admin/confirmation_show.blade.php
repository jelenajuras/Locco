@extends('layouts.admin')

@section('title', 'Naslovnica')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="odobrenje">
	<h3>Odobrenje zahtjeva</h3>

		<form name="contactform" method="get" target="_blank" action="{{ route('admin.confirmation') }}">
			<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="razlog" value=""><br>
			<input type="hidden" name="id" value="{{ $vacationRequest_id}}"><br>
			<input type="radio" name="odobreno" value="DA" checked> Odobreno
			<input type="radio" name="odobreno" value="NE" style="padding-left:20px;"> Nije odobreno<br>
			<input type="hidden" name="user_id" value="{{ Sentinel::getUser()->id }}"><br>
			<input type="hidden" name="datum_odobrenja" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"><br>

			<input class="odobri" type="submit" value="Pošalji">
		</form>

</div>
@stop
