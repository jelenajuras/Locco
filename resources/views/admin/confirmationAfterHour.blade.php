@extends('layouts.admin')

@section('title', 'Prekovremeni sati')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="odobrenje">
	<h3>Odobrenje prekovremenih sati</h3>
		<div class="form-group col-md-offset-1 col-md-10">
			<p><b>{{ $afterHour->employee['first_name'] .' '.  $afterHour->employee['last_name']}}</b></p>
			<p>{{ date('d.m.Y', strtotime($afterHour->datum)) }}</p>
			<p>{{ $afterHour->start_time . ' - ' . $afterHour->end_time }}</p>
			<p>{{ $afterHour->napomena }}</p>
		</div>
		<form name="contactform" method="get" action="{{ route('admin.confirmationAfter') }}">
			<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="razlog" value=""><br>
			<input type="hidden" name="id" value="{{ $afterHour->id }}"><br>
			<input type="radio" name="odobreno" value="DA" checked> Potvrđeno
			<input type="radio" name="odobreno" value="NE" style="padding-left:20px;"> Nije potvrđeno<br>
			<input type="hidden" name="user_id" value="{{ $nadredjeni1 }}"><br>
			<div class="form-group">
				<label>Odobreno prekovremenih sati:</label>
				<input name="odobreno_h" class="time form-control" type="time" value="{!! isset( $interval ) ? $interval : '00:00' !!}" id="date1" required>
			</div>
			<input type="hidden" name="datum_odobrenja" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"><br>
			<input class="odobri" type="submit" value="Pošalji">
		</form>
</div>
@stop
