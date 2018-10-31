@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>

@section('content')
	<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
		<h2>Zahtjev - Obavijest</h2>
		<div class="panel-body">
			 <form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.vacation_requests.store') }}"  onsubmit="return validateForm()">
				@if (Sentinel::inRole('administrator'))
					<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
						<label class="padd_10">Djelatnik</label>
						<select name="employee_id" value="{{ old('employee_id') }}" id="sel1" autofocus>
							<option selected="selected" value=""></option>
							@foreach ($registrations as $djelatnik)
								@if(!DB::table('employee_terminations')->where('employee_id',$djelatnik->employee_id)->first() )
									<option name="employee_id" value="{{ $djelatnik->employee_id }}">{{ $djelatnik->last_name  . ' ' . $djelatnik->first_name }}</option>
								@endif
							@endforeach	
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<p class="padd_10">moli da mu se odobri</p>
				@else
					<p class="padd_10">Ja, {{ $employee->first_name . ' ' . $employee->last_name }} molim da mi se odobri</p>
					<input name="employee_id" type="hidden" value="{{ $employee->id }}" />
				@endif
				

				<div class="form-group {{ ($errors->has('zahtjev')) ? 'has-error' : '' }}">
					<select class="{{ ($errors->has('zahtjev')) ? 'has-error' : '' }}" name="zahtjev" value="{{ old('zahtjev') }}" id="prikaz" oninput="this.className = ''" onchange="GO_value()">
						<option disabled selected value></option>
						<option class="editable1" value="GO">korištenje godišnjeg odmora za period od</option>
						<option class="editable2" value="Bolovanje">bolovanje</option>
						<option class="editable3"  value="Izlazak">izlazak</option>
						<option class="editable4" value="NPL">korištenje neplaćenog odmora za period od</option>
						<option class="editable5" value="SLD"  {{ ($slobodni_dani-$koristeni_slobodni_dani <= 0 ? 'disabled' : '' )  }} >korištenje slobodnih dana za period od</option>
					</select> 
					{!! ($errors->has('zahtjev') ? $errors->first('zahtjev', '<p class="text-danger">:message</p>') : '') !!}	
				</div>
				<p class="editOption4 iskorišteno" style="display:none;">
				<input type="hidden" value="{{ $dani_GO }}" name="Dani" />
					@if($dani_GO > 0)
						Neiskorišteno {{ $dani_GO }} dana godišnjeg odmora 
					@else
						Svi dani godišnjeg odmora su iskorišteni! <br>
						Nemoguće je poslati zahtjev za godišnji odmor.
						
					@endif
				</p>
				<p class="editOption5 iskorišteno" style="display:none;">
				<input type="hidden" value="{{ $dani_GO }}" name="Dani2" />
					@if( ($slobodni_dani -  $koristeni_slobodni_dani) > 0)
						Neiskorišteno {{ $slobodni_dani }} slobodnih dana
					@else
						Svi slobodni dani su iskorišteni! <br>
						Nemoguće je poslati zahtjev za slobodni dan.
					@endif
				</p>
				
				<div class="datum form-group editOption1" style="display:none;">
					<input name="GOpocetak" class="date form-control" type="text" value = "{{ old('GOpocetak')}}" id="date1" ><i class="far fa-calendar-alt" ></i>
					{!! ($errors->has('GOpocetak') ? $errors->first('GOpocetak', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<span class="editOption2 do"  style="display:none;">do</span>
				<div class="datum form-group editOption2" style="display:none">
					<input name="GOzavršetak" class="date form-control" type="text" value ="{{ old('GOzavršetak')}}"" id="date2"><i class="far fa-calendar-alt" ></i>
					{!! ($errors->has('GOzavršetak') ? $errors->first('GOzavršetak', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<p id="demo"></p>
				<div class="datum2 form-group editOption3" style="display:none;">
					<span>od</span><input type="time" name="vrijeme_od" class="vrijeme" value="08:00">
					<span>do</span><input 	type="time" name="vrijeme_do" class="vrijeme" value="16:00" >
				</div>
				<div class="napomena form-group padd_10 padd_20b {{ ($errors->has('napomena')) ? 'has-error' : '' }}">
					<label>Napomena:</label>
					<textarea rows="4" id="napomena" name="napomena" type="text" class="form-control" value="{{ old('napomena') }} "></textarea>
					{!! ($errors->has('napomena') ? $errors->first('napomena', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				
				<input name="_token" value="{{ csrf_token() }}" type="hidden">
				<input class="btn btn-lg btn-block editOption5" type="submit" value="Pošalji zahtjev" id="stil1" onclick="GO_dani()">
			</form>
		</div>
	</div>
		
		<script type="text/javascript">
			$('.date').datepicker({  
			   format: 'yyyy-mm-dd',
			   startDate:'-60y',
			   endDate:'+1y',
			}); 
		</script> 
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
		<!-- unos value u napomenu -->
		<script>
			function GO_value(){
				if(document.getElementById("prikaz").value == "GO" ){
					document.getElementById("napomena").value = "GO" ;
				}else {
					document.getElementById("napomena").value = "" ;
				}
			}
		</script>
		
		
		<!-- display  -->
		<script>
			$('#prikaz').change(function(){
			var selected = $('option:selected', this).attr('class');
			var optionText = $('.editable1').text();
			var optionText1 = $('.editable2').text();
			var optionText2 = $('.editable3').text();

			if(selected == "editable1"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption4').show();
			}
			if(selected == "editable2" || selected == "editable4" || selected == "editable5"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption4').hide();
			}
			if(selected == "editable5"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption5').show();
			}
			if(selected == "editable3"){
			  $('.editOption1').show();
			  $('.editOption2').hide();
			  $('.editOption3').show();
			  $('.editOption4').hide();
			}
			
			
			
			});
		</script>
		
@stop

