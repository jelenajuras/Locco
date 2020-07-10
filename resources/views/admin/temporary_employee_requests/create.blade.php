@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
	<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
		<h2 id="zahtjev">Zahtjev - Obavijest</h2>
		<div class="panel-body">
			 <form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.temporary_employee_requests.store') }}"  >
				@if (Sentinel::inRole('administrator'))
					<input 	name="role" hidden value="{!! Sentinel::inRole('administrator') ? 'admin' : 'basic' !!}"/>
					<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
						<label class="padd_10">Djelatnik</label>
						<select name="employee_id[]" value="{{ old('employee_id') }}" id="sel1" size="10" autofocus required >
							<option value="" disabled></option>
							@foreach ($temporaryEmployees as $employee)
								<option name="employee_id" value="{{ $employee->id }}">{{ $employee->last_name  . ' ' . $employee->first_name }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<p class="padd_10 text1 display-none">moli da mu se odobri</p>
					<p class="padd_10 text2 display-none">javio je da otvara</p>
				@else
					<p class="padd_10">Ja, {{ $temporaryEmployee->first_name . ' ' . $temporaryEmployee->last_name }} 
						<span class="text1 display-none">molim da mi se odobri</span>
						<span class="text2 display-none">obavještavam da otvaram</span>
					</p>
					<input name="employee_id" type="hidden" value="{{ $temporaryEmployee->id }}" />
				@endif
				<div class="form-group {{ ($errors->has('zahtjev')) ? 'has-error' : '' }}">
					<select class="{{ ($errors->has('zahtjev')) ? 'has-error' : '' }}" name="zahtjev" value="{{ old('zahtjev') }}" id="prikaz" oninput="this.className = ''" onchange="GO_value()" required>
						<option disabled selected value></option>
						<option class="editable1" value="SLD">slobodan dan</option>
						<option class="editable2" value="Bolovanje">bolovanje</option>
						<option class="editable3"  value="Izlazak">izlazak</option>
						<option class="editable6" value="VIK">oslobođenje od planiranog radnog vikenda</option>
					</select> 
					{!! ($errors->has('zahtjev') ? $errors->first('zahtjev', '<p class="text-danger">:message</p>') : '') !!}	
				</div>
				<div class="datum form-group editOption1 display-none {{ ($errors->has('start_date')) ? 'has-error' : '' }}" >
					<label class="padd_10">Za datum</label>
					<input name="start_date" class="date form-control" type="date" value = "{{ old('start_date')}}" id="date1" required><i class="far fa-calendar-alt"  ></i>
					{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			
				<div class="datum form-group editOption2 display-none">					
					<label class="padd_10">Zaključno sa datumom</label>
					<input name="end_date" class="date form-control" type="date" value ="{{ old('end_date')}}" id="date2"><i class="far fa-calendar-alt" ></i>
					{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="datum2 form-group editOption3 display-none">
					<span>od</span><input type="time" name="start_time" class="vrijeme" value="08:00">
					<span>do</span><input 	type="time" name="end_time" class="vrijeme" value="16:00" >
				</div>
				<div class="napomena form-group padd_10 padd_20b {{ ($errors->has('napomena')) ? 'has-error' : '' }}">
					<label>Napomena:</label>
					<textarea rows="4" id="napomena" name="napomena" type="text" class="form-control" required>{{ old('napomena') }}</textarea>
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
				<input class="btn btn-lg btn-block" type="submit" value="Pošalji zahtjev" id="stil1" >
			</form>
		</div>
	</div>
		<script src="{{ asset('js/vacation_req_show.js') }}"></script>
		<script src="{{ asset('js/go_value.js') }}"></script>
@stop