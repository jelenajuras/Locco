@extends('layouts.admin')

@section('title', 'Dodaj novo vozilo')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
	<h2>Novo vozilo</h2>
	<div class="panel-body">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.cars.store') }}">
			<div class="form-group {{ ($errors->has('proizvođač')) ? 'has-error' : '' }}">
				<label>Proizvođač</label>
				<input class="form-control" placeholder="Proizvođač" name="proizvođač" type="text" value="{{ old('proizvođač') }}" />
				{!! ($errors->has('proizvođač') ? $errors->first('proizvođač', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Model</label>
				<input class="form-control" placeholder="Model" name="model" type="text" value="{{ old('model') }}" />
				{!! ($errors->has('model') ? $errors->first('model', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Registracija</label>
				<input class="form-control" placeholder="Registracija" name="registracija" type="text" value="{{ old('registracija') }}" />
				{!! ($errors->has('registracija') ? $errors->first('registracija', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Broj šasije</label>
				<input class="form-control" placeholder="Broj šasije" name="šasija" type="text" value="{{ old('šasija') }}" />
				{!! ($errors->has('šasija') ? $errors->first('šasija', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Prva registracija</label>
				<input class="date form-control" placeholder="Prva registracija" type="text" name="prva_registracija" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
				{!! ($errors->has('prva_registracija') ? $errors->first('prva_registracija', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<script type="text/javascript">
				$('.date').datepicker({  
				   format: 'yyyy-mm-dd'
				 });  
			</script> 
			<div class="form-group">
				<label>Zadnja registracija</label>
				<input class="date form-control" placeholder="Zadnja registracija" type="text" name="zadnja_registracija" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
				{!! ($errors->has('zadnja_registracija') ? $errors->first('zadnja_registracija', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<script type="text/javascript">
				$('.date').datepicker({  
				   format: 'yyyy-mm-dd'
				 });  
			</script> 
			<div class="form-group">
				<label>Zadnji servis</label>
				<input class="date form-control" placeholder="Zadnji servis" type="text" name="zadnji_servis" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
				{!! ($errors->has('zadnji_servis') ? $errors->first('zadnji_servis', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<script type="text/javascript">
				$('.date').datepicker({  
				   format: 'yyyy-mm-dd'
				 });  
			</script> 
			<div class="form-group">
				<label>Trenutni kilometri</label>
				<input class="form-control" placeholder="Trenutni kilometri" name="trenutni_kilometri" type="text" value="{{ old('trenutni_kilometri') }}" />
				{!! ($errors->has('trenutni_kilometri') ? $errors->first('trenutni_kilometri', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Djelatnik</label>
				<select class="form-control" name="user_id">
					<option value="0"> </option>
					@foreach ($employees as $employee)
						@if(!DB::table('employee_terminations')->where('employee_id',$employee->id)->first() )
						<option name="user_id" value="{{ $employee->id }} ">{{ $employee->first_name . " " . $employee->last_name }}</option>
						@endif
					@endforeach
				</select>
			</div>

			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
		</form>
	</div>
</div>
@stop
