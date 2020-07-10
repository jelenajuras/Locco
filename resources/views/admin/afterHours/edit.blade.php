@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>

@section('content')
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
	<h2>Ispravak evidencije prekovremenog rada</h2>
	<div class="panel-body">
			<form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.afterHours.update', $afterHour->id) }}">
				<p class="padd_10">Ja, {{ $afterHour->employee['first_name'] . ' ' . $afterHour->employee['last_name '] }}molim da mi se potvrdi izvršen prekovremeni rad za projekt</p>
				<input name="employee_id" type="hidden" value="{{ $afterHour->employee_id }}" />
			<div class="form-group {{ ($errors->has('project_id')) ? 'has-error' : '' }}">
				<select id="select-state" name="project_id" placeholder="Pick a state..."  value="{{ old('project_id') }}" id="sel1" required>
					<option value="" disabled selected></option>
					@foreach ($projects as $project)
						<option class="project_list" name="project_id" {!! $project->erp_id == $afterHour->project_id ? 'selected' : '' !!} value="{{ intval($project->id) }}">{{ $project->erp_id  . ' ' . str_limit($project->naziv, 100)}}</option>
					@endforeach	
				</select>
			</div>
			<div class="datum form-group">
				<input name="datum" class="date form-control" type="date" value = "{{  date('Y-m-d', strtotime($afterHour->datum)) }}" id="date1" ><i class="far fa-calendar-alt" ></i>
				{!! ($errors->has('datum') ? $errors->first('datum', '<p class="text-danger">:message</p>') : '') !!}
			</div>

			<div class="datum2 form-group">
				<span>od</span><input type="time" name="start_time" class="vrijeme" value="{{ $afterHour->start_time }}">
				<span>do</span><input type="time" name="end_time" class="vrijeme" value="{{ $afterHour->end_time }}" >
			</div>
			<div class="napomena form-group padd_10 padd_20b {{ ($errors->has('napomena')) ? 'has-error' : '' }}">
				<label>Opis izvršenog rada:</label>
				<textarea rows="4" id="napomena" name="napomena" type="text" class="form-control">{{ $afterHour->napomena }}</textarea>
				{!! ($errors->has('napomena') ? $errors->first('napomena', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ method_field('PUT') }}
			{{ csrf_field() }}
			<input class="btn btn-lg btn-block editOption5" type="submit" value="Ispravi zahtjev" id="stil1" onclick="GO_dani()">
		</form>
	</div>
</div>
<script>
	$( ".date.form-control" ).change(function() {
		var date = $( this ).val();
		var now = new Date();
		var today = now.getFullYear() + '-' + ("0" + (now.getMonth()+1)).slice(-2) + '-' + ("0" + now.getDate()).slice(-2);
		
		var daybefore = new Date(now.setDate(now.getDate() - 1));
		var yesterday = daybefore.getFullYear() + '-' + ("0" + (daybefore.getMonth()+1)).slice(-2) + '-' + ("0" + daybefore.getDate()).slice(-2);
		console.log(date);
		console.log(today);
		console.log(yesterday);
		if( date == today || date == yesterday) {
			$('.editOption5').removeAttr('disabled');
		} else {
			alert("Zahtjev je moguće poslati samo na danas i jučer");
			$('.editOption5').attr('disabled','true');
		}
	});
	$(document).ready(function () {
		$('#select-state').selectize();
	});
</script>
@stop

