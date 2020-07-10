@extends('layouts.admin')

@section('title', 'Promjene podataka projekta')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
  <h2>Ispravi projekt</h2>
</div>
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
	<div class="panel-body">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.projects.update', $project->id) }}">
			<div class="form-group {{ ($errors->has('erp_id')) ? 'has-error' : '' }}">
				<text>Broj projekta (ERP)</text>
				<input class="form-control" placeholder="Broj projekta" name="erp_id" type="text" value="{{ $project->erp_id }}" required />
				{!! ($errors->has('erp_id') ? $errors->first('erp_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('customer_oib')) ? 'has-error' : '' }}">
				<text>OIB naručitelja</text>
				<input class="form-control" placeholder="OIB naručitelja" name="customer_oib" type="text" value="{{ $project->customer_oib }}" required/>
				{!! ($errors->has('customer_oib') ? $errors->first('customer_oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			
			<div class="form-group {{ ($errors->has('naziv')) ? 'has-error' : '' }}">
				<text>Naziv projekta</text>
			   <textarea class="form-control"  placeholder="Naziv projekta"  name="naziv" id="projekt-name" required>{{ $project->naziv }}</textarea>
				{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('objekt')) ? 'has-error' : '' }}">
				<text>Objekt</text>
				<input class="form-control" placeholder="Objekt - građevina" name="objekt" type="text" value="{{ $project->objekt }}" />
				{!! ($errors->has('objekt') ? $errors->first('objekt', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('user_id')) ? 'has-error' : '' }}">
				<text>Voditelj projekta</text>
				<select class="form-control" name="user_id"  id="sel1">
					<option disabled selected value> </option>
					@foreach($employees as $employee)
						<option name="user_id" {!! $project->user_id == $employee->id ? 'selected' : '' !!} value="{{$employee->id}}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input name="_token" value="{{ csrf_token() }}" type="hidden">
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Unesi promjene" id="stil1">
		</form>
	</div>
</div>

@stop
