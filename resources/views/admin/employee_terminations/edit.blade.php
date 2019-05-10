@extends('layouts.admin')

@section('title', 'Promjena odjave')

@section('content')
<div class="page-header">
  <h2>Promjena odjave radnika</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_terminations.update', $employee_termination->id ) }}">
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
						<label>Djelatnik:</label>
						<select class="form-control" name="employee_id" id="sel1">
							<option name="employee_id" value="{{ $employee_termination->employee_id }}">{{ $employee_termination->employee['first_name'] . ' ' . $employee_termination->employee['last_name'] }}</option>
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('otkaz_id'))  ? 'has-error' : '' }}">
						<label>Vrsta otkaza:</label>
						<select class="form-control" name="otkaz_id" id="sel1">
							<option selected name="otkaz_id" value="{{ $employee_termination->otkaz_id }}">{{ $employee_termination->termination['naziv'] }}</option>
							@foreach(DB::table('terminations')->orderBy('naziv','ASC')->get() as $otkaz)
								<option name="otkaz_id" value="{{ $otkaz->id }}">{{ $otkaz->naziv }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('otkaz_id') ? $errors->first('otkaz_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('otkazni_rok')) ? 'has-error' : '' }}">
						<label>Otkazni rok:</label>
						<input name="otkazni_rok" type="text" class="form-control" value="{{ $employee_termination->otkazni_rok }}">
					{!! ($errors->has('otkazni_rok') ? $errors->first('otkazni_rok', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Datum odjave: </label>
						<input name="datum_odjave" class="date form-control" type="date" value = "{{ date('Y-m-d', strtotime($employee_termination->datum_odjave)) }}">
					</div>
					<div class="form-group">
						<label>Napomena</label>
						<textarea class="form-control" name="napomena">{{ $employee_termination->napomena }}</textarea>
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi odjavu" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
@stop