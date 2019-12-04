@extends('layouts.admin')

@section('title', 'Upis novog naručitelja')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
	<h2>Novi klijent</h2>
</div>
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
	<div class="panel-body">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.customers.store') }}">
			<div class="form-group {{ ($errors->has('naziv')) ? 'has-error' : '' }}">
				<label>Naziv firme</label>
				<input class="form-control" placeholder="Naziv firme" name="naziv" type="text" value="{{ old('naziv') }}" />
				{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('adresa')) ? 'has-error' : '' }}">
				<label>Adresa</label>
				<input class="form-control" placeholder="Adresa" name="adresa" type="text" value="{{ old('adresa') }}" />
				{!! ($errors->has('adresa') ? $errors->first('adresa', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			
			<div class="form-group  {{ ($errors->has('grad')) ? 'has-error' : '' }}">
				<label>Grad</label>
				<input class="form-control" placeholder="Grad" name="grad" type="text" value="{{ old('grad') }}" />
				{!! ($errors->has('grad') ? $errors->first('grad', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('oib')) ? 'has-error' : '' }}">
				<label>OIB</label>
				<input class="form-control" placeholder="Adresa" name="oib" type="text" value="{{ old('oib') }}" />
				{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
		
		</form>
	</div>
</div>

@stop