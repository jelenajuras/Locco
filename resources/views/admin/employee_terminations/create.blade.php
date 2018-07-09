@extends('layouts.admin')

@section('title', 'Odjava')

@section('content')

<div class="row">
</br>
</br>
</br>
</br>
  <h1>Odjava radnika</h1>
</div> 
<div class="container">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_terminations.store' ) }}">
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
						<label>Djelatnik:</label>
						<select class="form-control" name="employee_id" id="sel1" value="{{ old('employee_id') }}">
							<option selected="selected" value=""></option>
							@foreach($employees as $employee)
							@if(!DB::table('employee_terminations')->where('employee_id',$employee->id)->first() )
								@if(DB::table('registrations')->where('employee_id',$employee->id)->first() )
								<option name="employee_id" value="{{ $employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }}</option>
							@endif
							@endif
							@endforeach	
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('otkaz_id'))  ? 'has-error' : '' }}">
						<label>Vrsta otkaza:</label>
						<select class="form-control" name="otkaz_id" id="sel1" value="{{ old('otkaz_id') }}">
							<option selected="selected" value=""></option>@foreach(DB::table('terminations')->orderBy('naziv','ASC')->get() as $otkaz)
								<option name="otkaz_id" value="{{ $otkaz->id }}">{{ $otkaz->naziv }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('otkaz_id') ? $errors->first('otkaz_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('otkazni_rok')) ? 'has-error' : '' }}">
						<label>Otkazni rok:</label>
						<input name="otkazni_rok" type="text" class="form-control" value="{{ old('otkazni_rok') }}">
					{!! ($errors->has('otkazni_rok') ? $errors->first('otkazni_rok', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Datum odjave: </label>
						<input name="datum_odjave" class="date form-control" type="text" value = "{{ Carbon\Carbon::now()->format('d-m-Y') }}">
					</div>
					<script type="text/javascript">
								$('.date').datepicker({  
								   format: 'dd-mm-yyyy'
								 });  
					</script>
					<div class="form-group">
						<label>Napomena</label>
						<textarea class="form-control" name="napomena">{{ old('napomena') }}</textarea>
					</div>
					
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
					<input class="btn btn-lg btn-primary btn-block" type="submit" value="Otkaži djelatnika" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop