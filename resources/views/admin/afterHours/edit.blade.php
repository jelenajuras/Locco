@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>

@section('content')
	<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
		<h2>Ispravak evidencije prekovremenog rada</h2>
			<div class="panel-body">
				 <form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.afterHours.update', $afterHour->id) }}">
				
						<p class="padd_10">Ja, {{ $afterHour->employee['first_name'] . ' ' . $afterHour->employee['last_name '] }}molim da mi se potvrdi izvršen prekovremeni rad dana</p>
						<input name="employee_id" type="hidden" value="{{ $employee->id }}" />
					
					<div class="datum form-group">
						<input name="datum" class="date form-control" type="date" value = "{{  date('Y-m-d', strtotime($afterHour->datum)) }}" id="date1" ><i class="far fa-calendar-alt" ></i>
						{!! ($errors->has('datum') ? $errors->first('datum', '<p class="text-danger">:message</p>') : '') !!}
					</div>

					<div class="datum2 form-group">
						<span>od</span><input type="time" name="vrijeme_od" class="vrijeme" value="{{ $afterHour->vrijeme_od }}">
						<span>do</span><input type="time" name="vrijeme_do" class="vrijeme" value="{{ $afterHour->vrijeme_do }}" >
					</div>
					<div class="napomena form-group padd_10 padd_20b {{ ($errors->has('napomena')) ? 'has-error' : '' }}">
						<label>Opis izvršenog rada:</label>
						<textarea rows="4" id="napomena" name="napomena" type="text" class="form-control">{{ $afterHour->napomena }}</textarea>
						{!! ($errors->has('napomena') ? $errors->first('napomena', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-block editOption5" type="submit" value="Ispravi zahtjev" id="stil1" onclick="GO_dani()">
				</form>
			</div>
		</div>
@stop

