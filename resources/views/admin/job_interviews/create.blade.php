@extends('layouts.admin')

@section('title', 'Prijava radnika')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
  <h2>Unesi razgovor</h2>
</div> 
<div class="">
	<div class="col-sm-8 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.job_interviews.store') }}">
					<div class="form-group {{ ($errors->has('first_name'))  ? 'has-error' : '' }}">
						<label>Ime:</label>
						<input name="first_name" type="text" class="form-control" value="{{ old('first_name') }}">
					</div>
					{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('last_name'))  ? 'has-error' : '' }}">
						<label>Prezime:</label>
						<input name="last_name" type="text" class="form-control" value="{{ old('last_name') }}">
					</div>
					{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('oib'))  ? 'has-error' : '' }}">
						<label>OIB:</label>
						<input name="oib" type="text" class="form-control" value="{{ old('oib') }}">
					</div>
					{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('email'))  ? 'has-error' : '' }}">
						<label>e-mail:</label>
						<input name="email" type="text" class="form-control" value="{{ old('email') }}">
					</div>
					{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('telefon'))  ? 'has-error' : '' }}">
						<label>Telefon:</label>
						<input name="telefon" type="text" class="form-control" value="{{ old('telefon') }}">
					</div>
					{!! ($errors->has('telefon') ? $errors->first('telefon', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('zvanje'))  ? 'has-error' : '' }}">
						<label>Zvanje:</label>
						<input name="zvanje" type="text" class="form-control" value="{{ old('zvanje') }}">
					</div>
					{!! ($errors->has('zvanje') ? $errors->first('zvanje', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('sprema'))  ? 'has-error' : '' }}">
						<label>Stručna sprema:</label>
						<select class="form-control" name="sprema" id="sel1">
							<option value="" disabled selected></option>
							<option name="sprema" value="SSS">{{ 'SSS' }}</option>
							<option name="sprema" value="VŠS">{{ 'VŠS' }}</option>
							<option name="sprema" value="VSS">{{ 'VSS' }}</option>
							<option name="sprema" value="NKV">{{ 'NKV' }}</option>
						</select>
					</div>
					{!! ($errors->has('sprema') ? $errors->first('sprema', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('radnoMjesto_id'))  ? 'has-error' : '' }}">
						<label>Radno mjesto:</label>
						<select class="form-control" name="radnoMjesto_id" id="sel1">
							<option value="" disabled selected></option>
							@foreach($works as $work)
								<option name="radnoMjesto_id" value="{{ $work->id }}">{{ $work->odjel . ' - '. $work->naziv }}</option>
							@endforeach	
						</select>
					</div>	
					{!! ($errors->has('radnoMjesto_id') ? $errors->first('radnoMjesto_id', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('datum'))  ? 'has-error' : '' }}">
						<label>Datum razgovora:</label>
						<input name="datum" class="date form-control" type="date" value = "{{ Carbon\Carbon::now()->format('Y-m-d') }}">
					{!! ($errors->has('datum') ? $errors->first('datum', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('godine_iskustva'))  ? 'has-error' : '' }}">
						<label>Godine iskustva:</label>
						<input name="godine_iskustva" type="text" class="form-control"  value="{{ old('godine_iskustva') }}">
					</div>
					{!! ($errors->has('godine_iskustva') ? $errors->first('godine_iskustva', '<p class="text-danger">:message</p>') : '') !!}
					<div class="placa form-group {{ ($errors->has('placa'))  ? 'has-error' : '' }}">
						<label>Plaća:</label>
						<input name="placa" type="text" class="form-control"  value="{{ old('placa') }}">
						<span> Kn</span>
					</div>
					{!! ($errors->has('placa') ? $errors->first('placa', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('jezik'))  ? 'has-error' : '' }}">
						<label>Strani jezik:</label>
						<input name="jezik" type="text" class="form-control"  value="{{ old('jezik') }}">
					</div>
					{!! ($errors->has('jezik') ? $errors->first('jezik', '<p class="text-danger">:message</p>') : '') !!}
					
					<script type="text/javascript">
								$('.date').datepicker({  
								   format: 'dd-mm-yyyy'
								 });  
					</script> 
					<div class="form-group">
						<label>Napomena: </label>
						<textarea class="form-control" name="napomena"></textarea>
					</div>
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Unesi razgovor" id="stil1">
				</form>

			</div>
		</div>
	</div>
</div>

@stop