@extends('layouts.admin')

@section('title', 'Dodaj novi projekt')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
  <h2>Novi projekt</h2>
</div>
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
	<div class="panel-body">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.projects.store') }}">
			<div class="form-group {{ ($errors->has('id')) ? 'has-error' : '' }}">
				<text>Broj projekta</text>
				<input class="form-control" placeholder="Broj projekta" name="id" type="text" value="{{ old('id') }}" />
				{!! ($errors->has('id') ? $errors->first('id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('customer_id')) ? 'has-error' : '' }}">
				<text>Naručitelj</text>
				<select class="form-control" name="customer_id" id="sel1">
					<option disabled selected value> </option>
					@foreach($customers as $customer)
						<option name="customer_id" value=" {{$customer->id}} ">{{ $customer->naziv }}</option>
					@endforeach
				</select>
				{!! ($errors->has('customer_id') ? $errors->first('customer_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<text>Investitor</text>
				<select class="form-control" name="investitor_id"  id="sel1">
					<option disabled selected value> </option>
					@foreach($customers as $customer)
						<option name="investitor_id" value=" {{$customer->id}} ">{{ $customer->naziv }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group {{ ($errors->has('naziv')) ? 'has-error' : '' }}">
				<text>Naziv projekta</text>
			   <textarea class="form-control"  placeholder="Naziv projekta"  name="naziv" id="projekt-name"></textarea>
				{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('objekt')) ? 'has-error' : '' }}">
				<text>Objekt</text>
				<input class="form-control" placeholder="Objekt - građevina" name="objekt" type="text" value="{{ old('objekt') }}" />
				{!! ($errors->has('objekt') ? $errors->first('objekt', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<text>Voditelj projekta</text>
				<select class="form-control" name="user_id"  id="sel1">
					<option disabled selected value> </option>
					@foreach($employees as $employee)
						<option name="user_id" value=" {{$employee->id}} ">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
					@endforeach
				</select>
			</div>
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši projekt" id="stil1">
		</form>
	</div>
        
</div>
@stop
