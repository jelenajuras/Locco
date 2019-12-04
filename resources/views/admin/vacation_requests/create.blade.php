@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>

@section('content')
	<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
		<h2 id="zahtjev">Zahtjev - Obavijest</h2>
		<div class="panel-body">
			 <form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.vacation_requests.store') }}"  onsubmit="return validateForm()">
				@if (Sentinel::inRole('administrator'))
				<input 	name="role" hidden value="{!! Sentinel::inRole('administrator') ? 'admin' : 'basic' !!}"/>
					<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
						<label class="padd_10">Djelatnik</label>
						<select name="employee_id[]" value="{{ old('employee_id') }}" id="sel1" size="10" autofocus multiple required >
							<option value="" disabled></option>
							<option name="employee_id" value="svi">Svi djelatnici</option>
							@foreach ($registrations as $djelatnik)
								@if(!DB::table('employee_terminations')->where('employee_id',$djelatnik->employee_id)->first() )
									<option name="employee_id" value="{{ $djelatnik->employee_id }}">{{ $djelatnik->last_name  . ' ' . $djelatnik->first_name }}</option>
								@endif
							@endforeach	
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<p class="padd_10 text1 display-none">moli da mu se odobri</p>
					<p class="padd_10 text2 display-none">javio je da otvara</p>
				@else
					<p class="padd_10">Ja, {{ $employee->first_name . ' ' . $employee->last_name }} 
						<span class="text1 display-none">molim da mi se odobri</span>
						<span class="text2 display-none">obavještavam da otvaram</span>
					</p>
					<input name="employee_id" type="hidden" value="{{ $employee->id }}" />
				@endif
				<!--<input name="montaza" type="hidden"  value="{{ $registration->work['job_description']}}" />-->
				<div class="form-group {{ ($errors->has('zahtjev')) ? 'has-error' : '' }}">
					<select class="{{ ($errors->has('zahtjev')) ? 'has-error' : '' }}" name="zahtjev" value="{{ old('zahtjev') }}" id="prikaz" oninput="this.className = ''" onchange="GO_value()" required>
						<option disabled selected value></option>
						<option class="editable1" value="GO">korištenje godišnjeg odmora za period od</option>
						<option class="editable2" value="Bolovanje">bolovanje</option>
						<option class="editable3"  value="Izlazak">izlazak</option>
						<option class="editable4" value="NPL">korištenje neplaćenog dopusta za period od</option>
						<option class="editable7" value="PL">korištenje plaćenog dopusta za period od</option>
						<option class="editable6" value="VIK">oslobođenje od planiranog radnog vikenda</option>
						<option class="editable5" value="SLD"  {{ ($slobodni_dani-$koristeni_slobodni_dani <= 0 && !Sentinel::inRole('administrator') ? 'disabled' : '' )  }} >korištenje slobodnih dana za period od</option>
					</select> 
					{!! ($errors->has('zahtjev') ? $errors->first('zahtjev', '<p class="text-danger">:message</p>') : '') !!}	
				</div>
				<p class="editOption4 iskorišteno display-none" >
					<input type="hidden" value="{{ $preostali_dani }}" name="Dani" />
					@if($preostali_dani > 0)
							Neiskorišteno {{ $preostali_dani }} dana razmjernog godišnjeg odmora 
					@else
							Svi dani godišnjeg odmora su iskorišteni! <br>
							Nemoguće je poslati zahtjev za godišnji odmor.
					@endif
				</p>
				<p class="editOption5 iskorišteno display-none">
					@if( ($slobodni_dani -  $koristeni_slobodni_dani) > 0)
						Neiskorišteno {{ $slobodni_dani -  $koristeni_slobodni_dani }} slobodnih dana
					@else
						Svi slobodni dani su iskorišteni! <br>
						Nemoguće je poslati zahtjev za slobodni dan.
					@endif
				</p>
				<div class="datum form-group editOption1 display-none {{ ($errors->has('GOpocetak')) ? 'has-error' : '' }}" >
					<input name="GOpocetak" class="date form-control" type="date" value = "{{ old('GOpocetak')}}" id="date1" required><i class="far fa-calendar-alt"  ></i>
					{!! ($errors->has('GOpocetak') ? $errors->first('GOpocetak', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<span class="editOption2 do display-none" >do</span>
				<div class="datum form-group editOption2 display-none">
					<input name="GOzavršetak" class="date form-control" type="date" value ="{{ old('GOzavršetak')}}" id="date2"><i class="far fa-calendar-alt" ></i>
					{!! ($errors->has('GOzavršetak') ? $errors->first('GOzavršetak', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="datum2 form-group editOption3 display-none">
					<span>od</span><input type="time" name="vrijeme_od" class="vrijeme" value="08:00">
					<span>do</span><input 	type="time" name="vrijeme_do" class="vrijeme" value="16:00" >
				</div>
				<div class="napomena form-group padd_10 padd_20b {{ ($errors->has('napomena')) ? 'has-error' : '' }}">
					<label>Napomena:</label>
					<textarea rows="4" id="napomena" name="napomena" type="text" class="form-control" value="{{ old('napomena') }} " required></textarea>
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
		<div class="uputa_RGO display-none">
			<p>*** Napomena:</p>
			<p>Razmjerni dio godišnjeg odmora za tekuću godinu utvrđuje se u trajanju od 1/12 godišnjeg odmora za svaki mjesec trajanja radnog odnosa u Duplicu u tekućoj godini.<br>
				Zahtjev možete predati samo za razmjeran dio u trenutku generiranja zahtjeva. Ukoliko su iskorišteni svi dani razmjernog dijela godišenjeg odmora nećete moći generirati zahtjev.<br>
				Zahtjev se ne može poslati za tekući dan.  <br>
			</p>
		</div>
		<div class="uputa_NPL display-none">
			<p>*** Napomena:</p>
			<p>Sukladno Zakonu o radu, za vrijeme neplaćenog dopusta prava i obveze iz radnog odnosa miruju.
			</p>
		</div>
		<div class="uputa_PL display-none">
			<p>*** Napomena:</p>
			<p>Tijekom kalendarske godine Radnik ima pravo na plaćeni dopust za važne osobne potrebe u ukupnom trajanju do 7 radnih dana godišnje, u trajanju kako je niže navedeno:<br>
			- sklapanje braka - 3 radna dana<br>
			- rođenje djeteta - 2 radna dana<br>
			- smrt bračnog supružnika, djeteta ili roditelja - 4 radna dana<br>
			- smrt roditelja bračnog supružnika, djedova i baka, braće i sestara - 1 radni dan<br>
			- selidba u isto ili drugo mjesto - 1 radni dan<br>
			- uklanjanje štetnih posljedica elementarnih nesreća na stambenim i gospodarskim objektima - 4 radna dana<br>
			- teška bolest člana uže obitelji (roditelji, djeca) izvan mjesta stanovanja - 2 radna dana<br>
			- u slučaju dobrovoljnog darivanja krvi - 1 radni dan.<br>

			Okolnosti temeljem kojih traži pravo na plaćeni dopust, Radnik mora dokazati pisanim putem.<br>
			</p>
		</div>
	</div>
		
		<!-- izračun dana GO u zahtjevu -->
		<script>
			function GO_dani(){
				if(document.getElementById("prikaz").value == "GO" ){
					var dan1 =  new Date(document.forms["myForm"]["GOpocetak"].value);
					var dan2 = new Date(document.forms["myForm"]["GOzavršetak"].value);
					var person = {GOpocetak:dan1, GOzavršetak:dan2};
					
					//razlika dana
					var datediff = (dan2 - dan1);
					document.getElementById('demo').innerHTML=(datediff / (24*60*60*1000)) +1;
					//uvečava datum
					dan1.setDate(dan1.getDate() + 1);
					//document.getElementById('demo').innerHTML=dan1;
				}
				return false;
			}
		</script>
		<!-- validator  -->
		<script>
			function validateForm() {
				var x = document.forms["myForm"]["zahtjev"].value;
				var y = document.forms["myForm"]["Dani"].value;
				var z = document.forms["myForm"]["GOpocetak"].value;
				var role = document.forms["myForm"]["role"].value;
				console.log(role);
				if (z == "") {
					alert("Nemoguće poslati zahtjev. Nije upisan početan datum");
					return false;
				}
				if(role !='admin'){
					if (x == "GO" & y <= 0) {
						alert("Nemoguće poslati zahtjev. Svi dani godišnjeg odmora su iskorišteni");
					//	return false;
					}
				}
				
				
			}
		</script>
		

		<script src="{{ asset('js/vacation_req_show.js') }}"></script>
		<script src="{{ asset('js/go_value.js') }}"></script>
		
@stop