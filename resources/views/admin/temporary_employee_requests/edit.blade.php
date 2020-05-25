@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
	<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
		<h2 id="zahtjev">Zahtjev - Obavijest</h2>
		<div class="panel-body">
			 <form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.temporary_employee_requests.update', $request->id ) }}"  >
				<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
					<label class="padd_10">Djelatnik</label>
					<select name="employee_id" value="{{ old('employee_id') }}" id="sel1" autofocus required >
						@foreach ($temporaryEmployees as $employee)
							<option name="employee_id" value="{{ $employee->id }}" {!! $request->employee_id == $employee->id ? 'selected' : '' !!}>{{ $employee->last_name  . ' ' . $employee->first_name }}</option>
						@endforeach	
					</select>
					{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>				
				<div class="form-group {{ ($errors->has('zahtjev')) ? 'has-error' : '' }}">
					<label class="padd_10">Zahtjev</label>
					<select class="{{ ($errors->has('zahtjev')) ? 'has-error' : '' }}" name="zahtjev" value="{{ old('zahtjev') }}" id="prikaz" oninput="this.className = ''" onchange="GO_value()" required>
						<option disabled selected value></option>
						<option class="editable1" value="SLD" {!! $request->zahtjev == 'SLD' ? 'selected' : '' !!} >slobodan dan</option>
						<option class="editable2" value="Bolovanje" {!! $request->zahtjev == 'Bolovanje' ? 'selected' : '' !!}>bolovanje</option>
						<option class="editable3"  value="Izlazak" {!! $request->zahtjev == 'Izlazak' ? 'selected' : '' !!}>izlazak</option>
						<option class="editable6" value="VIK" {!! $request->zahtjev == 'VIK' ? 'selected' : '' !!}>oslobođenje od planiranog radnog vikenda</option>
					</select> 
					{!! ($errors->has('zahtjev') ? $errors->first('zahtjev', '<p class="text-danger">:message</p>') : '') !!}	
				</div>
				<div class="datum form-group editOption1 display-none {{ ($errors->has('GOpocetak')) ? 'has-error' : '' }}" >
					<label class="padd_10">Za datum</label>
					<input name="GOpocetak" class="date form-control" type="date" value = "{{ $request->GOpocetak}}" id="date1" required><i class="far fa-calendar-alt"  ></i>
					{!! ($errors->has('GOpocetak') ? $errors->first('GOpocetak', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="datum form-group editOption2 display-none">					
					<label class="padd_10">Zaključno sa datumom</label>
					<input name="GOzavršetak" class="date form-control" type="date" value ="{{ $request->GOzavršetak }}" id="date2"><i class="far fa-calendar-alt" ></i>
					{!! ($errors->has('GOzavršetak') ? $errors->first('GOzavršetak', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="datum2 form-group editOption3 display-none">
					<span>od</span><input type="time" name="vrijeme_od" class="vrijeme" value="{!! $request->vrijeme_od ?  $request->vrijeme_od : '08:00' !!}">
					<span>do</span><input 	type="time" name="vrijeme_do" class="vrijeme" value="{!! $request->vrijeme_do ?  $request->vrijeme_od : '16:00' !!}" >
				</div>
				<div class="napomena form-group padd_10 padd_20b {{ ($errors->has('napomena')) ? 'has-error' : '' }}">
					<label>Napomena:</label>
					<textarea rows="4" id="napomena" name="napomena" type="text" class="form-control" required>{{ $request->napomena }}</textarea>
					{!! ($errors->has('napomena') ? $errors->first('napomena', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				@if (Sentinel::inRole('administrator'))
					<div class="form-group">
						<label for="email">Slanje emaila:</label>
						<input type="radio" name="email" value="DA" checked> Poslati e-mail<br>
						<input type="radio" name="email" value="NE"> Ne slati mail
					</div>
				@else
					<input type="hidden" name="email" value="DA">
				@endif
				{{ csrf_field() }}
				{{ method_field('PUT') }}
				<input class="btn btn-lg btn-block" type="submit" value="Pošalji zahtjev" id="stil1" >
			</form>
		</div>
	</div>
		<script src="{{ asset('js/vacation_req_show.js') }}"></script>
		<script src="{{ asset('js/go_value.js') }}"></script>
@stop