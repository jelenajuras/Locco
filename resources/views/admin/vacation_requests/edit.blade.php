@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
	<div class="forma col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<h2>Ispravak zahtjeva</h2>
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.vacation_requests.update', $vacationRequest->id) }}">
					<p>Ja, {{ $employee->first_name . ' ' . $employee->last_name }} molim da mi se odobri </p>
					<select name="zahtjev" id="prikaz" oninput="this.className = ''" >
						<option class="editable1" value="GO" {!! ($vacationRequest->zahtjev == 'GO' ? 'selected ': '') !!}>korištenje godišnjeg odmora za period od</option >
							<option class="editable1" value="RD" {!! ($vacationRequest->zahtjev == 'RD' ? 'selected ': '') !!} >rad od doma</option>
						<option class="editable1" value="COVID-19" {!! ($vacationRequest->zahtjev == 'COVID-19' ? 'selected ': '') !!}>Oslobođenje od rada - COVID-19</option>
						<option class="editable8" value="CEK"  {!! ($vacationRequest->zahtjev == 'CEK' ? 'selected ': '') !!} >čekanje</option>
						<option class="editable2" value="Bolovanje" {!! ($vacationRequest->zahtjev == 'Bolovanje' ? 'selected ': '') !!}>bolovanje</option >
						<option class="editable3"  value="Izlazak" {!! ($vacationRequest->zahtjev == 'Izlazak' ? 'selected ': '') !!}>Prijevremeni izlaz dana</option >
						<option class="editable4" value="NPL" {!! ($vacationRequest->zahtjev == 'NPL' ? 'selected ': '') !!}>korištenje neplaćenog dopusta za period od</option>
						<option class="editable7" value="PL" {!! ($vacationRequest->zahtjev == 'PL' ? 'selected ': '') !!}>korištenje plaćenog dopusta za period od</option>
						<option class="editable6" value="VIK" {!! ($vacationRequest->zahtjev == 'VIK' ? 'selected ': '') !!}>oslobođenje od planiranog radnog vikenda</option>
						<option class="editable5" value="SLD"  {!! ($slobodni_dani-$koristeni_slobodni_dani <= 0 ? 'disabled' : '' )  !!} {!! ($vacationRequest->zahtjev == 'SLD' ? 'selected ': '') !!} >korištenje slobodnih dana za period od</option>
					</select> 
					<input name="employee_id" type="hidden" value="{{ $employee->id }}" />
					<input name="montaza" type="hidden"  value="{{ $registration->work['job_description']}}" />
						@if($registration->work['job_description'] != 'montaža')
							<p class="editOption4 iskorišteno" style="display:none;">
								<input type="hidden" value="{{ $preostali_dani }}" name="Dani" />
								@if($preostali_dani > 0)
										Neiskorišteno {{ $preostali_dani }} dana razmjernog godišnjeg odmora 
								@else
										Svi dani godišnjeg odmora su iskorišteni! <br>
										Nemoguće je poslati zahtjev za godišnji odmor.
								@endif
							</p>
							<p class="editOption5 iskorišteno" style="display:none;">
								@if( ($slobodni_dani -  $koristeni_slobodni_dani) > 0)
									Neiskorišteno {{ $slobodni_dani -  $koristeni_slobodni_dani }} slobodnih dana
								@else
									Svi slobodni dani su iskorišteni! <br>
									Nemoguće je poslati zahtjev za slobodni dan.									
								@endif
							</p>
						@endif
					<div class="datum form-group editOption1" >
						<input name="start_date" class="date form-control" type="date" value = "{{  date('Y-m-d', strtotime($vacationRequest->start_date)) }}" required><i class="far fa-calendar-alt"></i>
						{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<span class="editOption2 do" >do</span>
					<div class="datum form-group editOption2">
						<input name="end_date" class="date form-control" type="date" value = "{{  date('Y-m-d', strtotime($vacationRequest->end_date)) }}" ><i class="far fa-calendar-alt"></i>
						{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="datum2 form-group editOption3" {!! ($vacationRequest->zahtjev == 'GO' || $vacationRequest->zahtjev == 'Bolovanje' ? ' style="display:none;" ': '') !!}>
						<span>od</span><input type="time" name="start_time" class="vrijeme" value={!! !$vacationRequest->start_time ? '08:00' : $vacationRequest->start_time !!} >
						<span>do</span><input type="time" name="end_time" class="vrijeme" value={!! !$vacationRequest->end_time ? '16:00' : $vacationRequest->end_time !!} }}" >
					</div>
					<div class="form-group padd_10 { ($errors->has('sprema')) ? 'has-error' : '' }}" style="clear:left">
						<label>Napomena:</label>
						<textarea rows="4" name="napomena" maxlength="500" type="text" class="form-control">{{ $vacationRequest->napomena }}</textarea>
					</div>
					@if (Sentinel::inRole('administrator'))
						<div class="form-group">
							<label for="email">Slanje emaila:</label>
							<input type="radio" name="email" value="DA" > Poslati e-mail<br>
							<input type="radio" name="email" value="NE" checked> Ne slati mail
						</div>
					@endif
					{{ method_field('PUT') }}
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-block" type="submit" value="Ispravi zahtjev" id="stil1">
				</form>
			</div>
		</div>
		<script>
			function GO_dani(){
				if(document.getElementById("prikaz").value == "GO" ){
					var dan1 =  new Date(document.forms["myForm"]["start_date"].value);
					var dan2 = new Date(document.forms["myForm"]["end_date"].value);
					var person = {start_date:dan1, end_date:dan2};
					//razlika dana
					var datediff = (dan2 - dan1);
					document.getElementById('demo').innerHTML=(datediff / (24*60*60*1000)) +1;
					//uvečava datum
					dan1.setDate(dan1.getDate() + 1);
					
					//document.getElementById('demo').innerHTML=dan1;
				}
			}
		</script>
		<!-- validator  -->
		<script>
			function validateForm() {
				var x = document.forms["myForm"]["zahtjev"].value;
				var y = document.forms["myForm"]["Dani"].value;
				if (x == "GO" & y <= 0) {
					alert("Nemoguće poslati zahtjev. Svi dani godišnjeg odmora su iskorišteni");
					return false;
				}
			}
		</script>
	
		<script src="{{ asset('js/vacation_req_show.js') }}"></script>
		<script src="{{ asset('js/go_value.js') }}"></script>
@stop

