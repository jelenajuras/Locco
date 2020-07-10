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
			<div class="form-group {{ ($errors->has('id')) ? 'has-error' : '' }}">
				<label>Broj projekta {{ $project->id }}</label>
				<input class="form-control" placeholder="Broj projekta" name="id" type="hidden" value="{{ $project->id }}" />
			</div>
			<div class="form-group">
				<label>Naručitelj</label>
				<select class="form-control" name="customer_id" id="sel1">	
					@foreach ($customers as $customer)
						<option name="customer_id" value="{{ $customer->id }}"  {!! ( $customer->id == $project->customer_id ? 'selected ': '') !!}>{{ $customer->naziv }}</option>
					@endforeach	
				</select>
			</div>
			<div class="form-group">
				<label>Investitor</label>
				<select class="form-control" name="investitor_id" value="" id="sel1">
					@foreach ($customers as $customer)
						<option name="investitor_id" value="{{$customer->id}}"  {!! ($project->investitor_id ==  $customer->id ? 'selected ': '') !!}>{{ $customer->naziv }}</option>
					@endforeach	
				</select>
			</div>
			<div class="form-group {{ ($errors->has('naziv')) ? 'has-error' : '' }}">
			   <label>Naziv projekta</label>
			   <textarea class="form-control" name="naziv" id="projekt-name" >{{ $project->naziv }}</textarea>
			   
			</div>
			<div class="form-group {{ ($errors->has('objekt')) ? 'has-error' : '' }}">
				<label>Objekt</label>
				<input class="form-control" placeholder="Objekt - građevina" name="objekt" type="text" value="{{ $project->objekt }}" />
				{!! ($errors->has('objekt') ? $errors->first('objekt', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Voditelj projekta</label>
				<select class="form-control" name="user_id"  id="sel1">
					<option disabled selected value></option>
					@foreach($employees as $employee)
						@if(!DB::table('employee_terminations')->where('employee_id',$employee->id)->first() )
							<option name="user_id" value="{{ $employee->id }}" {!! ($project->user_id ==  $employee->id ? 'selected ': '') !!}>{{ $employee->first_name . ' ' . $employee->last_name }}</option>
						@endif
					@endforeach
				</select>
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input name="_token" value="{{ csrf_token() }}" type="hidden">
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Unesi promjene" id="stil1">
		</form>
	</div>
</div>

@stop
