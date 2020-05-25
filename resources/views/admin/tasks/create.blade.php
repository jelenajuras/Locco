@extends('layouts.admin')

@section('title', 'Novi zadatak')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class"row">
	<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
	</a>
	<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-4 col-lg-offset-4">
		<h2 id="zahtjev">Dodaj zadatak</h2>
		<div class="panel-body">
			<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.tasks.store') }}">
				<div class="form-group {{ ($errors->has('task')) ? 'has-error' : '' }}">
					<label>Zadatak</label>
					<textarea class="form-control" maxlength="255" name="task" required>{{ old('task') }}</textarea>
					{!! ($errors->has('id') ? $errors->first('id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('to_employee_id')) ? 'has-error' : '' }}">
					<label>Zaduženi djelatnik</label>
					<select class="form-control" name="to_employee_id[]"  multiple required >
						<option disabled selected value></option>
						@foreach($employees as $employee)
							<option name="to_employee_id" value="{{ $employee->employee_id }}">{{ $employee->last_name . ' ' . $employee->first_name }}</option>
						@endforeach
					</select>
					{!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>		
				<div class="form-group {{ ($errors->has('start_date')) ? 'has-error' : '' }}">
					<label>Datum</label>
					<input class="form-control" name="start_date" type="date" value="{{ old('start_date') }}" required />
					{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('end_date')) ? 'has-error' : '' }}">
					<label>Završni datum</label>
					<input class="form-control" name="end_date" type="date" value="{{ old('end_date') }}" />
					{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('interval'))  ? 'has-error' : '' }} clear_l" id="period">
					<label class="label_period">Period ponavljanja</label>
					<select class="form-control period" name="interval" value="{{ old('interval') }}" required >
						<option class="no_repeat" value="no_repeat">Bez ponavljanja</option>
						<option value="every_day">Dnevno</option>
						<option value="once_week">Tjedno</option>
						<option value="once_month">Mjesečno</option>
						<option value="once_year">Godišnje</option>
					{{-- 	<option value="customized">Prilagođeno</option> --}}
					</select>
				</div>
			{{-- 	<div class="form-group clear_l" id="interval" >
					<label class="label_custom_interal">Prilagođeno ponavljanje</label>
					<input class="form-control input_interval" type="number" min="0" name="interval" value="{{ old('interval') }}" />
					<select  class="form-control select_period" name="period" value="{{ old('period') }}"  >
						<option value="day">dan</option>
						<option value="month">tjedan</option>
						<option value="week">mjesec</option>
						<option value="year">godina</option>
					</select>
				</div> --}}
				<div class="form-group {{ ($errors->has('active')) ? 'has-error' : '' }}">
					<label>Status</label>
					<label class="status" for="active_1">Aktivan <input name="active" type="radio" value="1" id="active_1" checked /></label>
					<label class="status" for="active_0">Neaktivan <input name="active" type="radio" value="0" id="active_0" /></label>
					{!! ($errors->has('active') ? $errors->first('active', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				{{ csrf_field() }}
				<input class="btn btn-lg btn-primary btn-block" type="submit" value="Spremi zadatak" id="stil1">
			</form>
		</div>
	</div>
</div>
<script>
	/* $.getScript( '/../js/task.js'); */
</script>

@stop
