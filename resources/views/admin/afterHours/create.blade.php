@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
	<h2>Evidencija prekovremenog rada</h2>
	<div class="panel-body">
			<form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.afterHours.store') }}">
				@if (Sentinel::inRole('administrator'))
					<input 	name="role" hidden value="{!! Sentinel::inRole('administrator') ? 'admin' : 'basic' !!}"/>
						<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
							<label class="padd_10">Djelatnik</label>
							<select name="employee_id[]" value="{{ old('employee_id') }}" id="sel1" size="10" autofocus multiple required >
								<option value="" disabled></option>
								@foreach ($registrations as $djelatnik)
									@if(!DB::table('employee_terminations')->where('employee_id',$djelatnik->employee_id)->first() )
										<option name="employee_id" value="{{ $djelatnik->employee_id }}">{{ $djelatnik->last_name  . ' ' . $djelatnik->first_name }}</option>
									@endif
								@endforeach	
							</select>
							{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<p class="padd_10 text1 ">moli da mu se potvrdi izvršen prekovremeni rad dana</p>
				@else
					<p class="padd_10">Ja, {{ $employee->first_name . ' ' . $employee->last_name }} molim da mi se potvrdi izvršen prekovremeni rad dana</p>
					<input name="employee_id" type="hidden" value="{{ $employee->id }}" />
				@endif
			<div class="datum form-group">
				<input name="datum" class="date form-control" type="date" value = "{{ old('datum')}}" id="date1" required><i class="far fa-calendar-alt" ></i>
				{!! ($errors->has('datum') ? $errors->first('datum', '<p class="text-danger">:message</p>') : '') !!}
			</div>

			<div class="datum2 form-group">
				<span>od</span><input type="time" name="vrijeme_od" class="vrijeme" value="08:00" required>
				<span>do</span><input type="time" name="vrijeme_do" class="vrijeme" value="16:00" required>
			</div>
			<div class="napomena form-group padd_10 padd_20b {{ ($errors->has('napomena')) ? 'has-error' : '' }}">
				<label>Opis izvršenog rada:</label>
				<textarea rows="4" id="napomena" name="napomena" type="text" class="form-control" value="{{ old('napomena') }} " required></textarea>
				{!! ($errors->has('napomena') ? $errors->first('napomena', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			
			{{ csrf_field() }}
			<input class="btn btn-lg btn-block editOption5" type="submit" value="Pošalji zahtjev" id="stil1" onclick="GO_dani()">
			<span class="role_admin" hidden >{{ Sentinel::inRole('administrator') }}</span>
		</form>
	</div>
</div>
<script>
console.log( ! $('.role_admin').text());
	$( ".date.form-control" ).change(function() {
		if( ! $('.role_admin').text()) {
			var date = $( this ).val();
			var now = new Date();
			var today = now.getFullYear() + '-' + ("0" + (now.getMonth()+1)).slice(-2) + '-' + ("0" + now.getDate()).slice(-2);
			
			var daybefore = new Date(now.setDate(now.getDate() - 1));
			var yesterday = daybefore.getFullYear() + '-' + ("0" + (daybefore.getMonth()+1)).slice(-2) + '-' + ("0" + daybefore.getDate()).slice(-2);
			console.log(date);
			console.log(today);
			console.log(yesterday);

			if( date == today || date == yesterday) {
				$('.editOption5').removeAttr('disabled');
			} else {
				alert("Zahtjev je moguće poslati samo na danas i jučer");
				$('.editOption5').attr('disabled','true');
			}
		}
	});
</script>
@stop

