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
						<option class="editable2" value="Bolovanje" {!! ($vacationRequest->zahtjev == 'Bolovanje' ? 'selected ': '') !!}>bolovanje</option >
						<option class="editable3"  value="Izlazak" {!! ($vacationRequest->zahtjev == 'Izlazak' ? 'selected ': '') !!}>Prijevremeni izlaz dana</option >
						<option class="editable4" value="NPL" {!! ($vacationRequest->zahtjev == 'NPL' ? 'selected ': '') !!}>korištenje neplaćenog odmora za period od</option>
						<option class="editable7" value="PL" {!! ($vacationRequest->zahtjev == 'PL' ? 'selected ': '') !!}>korištenje plaćenog odmora za period od</option>
						<option class="editable6" value="VIK" {!! ($vacationRequest->zahtjev == 'VIK' ? 'selected ': '') !!}>oslobođenje od planiranog radnog vikenda</option>
						<option class="editable5" value="SLD"  {!! ($slobodni_dani-$koristeni_slobodni_dani <= 0 ? 'disabled' : '' )  !!} {!! ($vacationRequest->zahtjev == 'SLD' ? 'selected ': '') !!} >korištenje slobodnih dana za period od</option>
					</select> 
					<input name="employee_id" type="hidden" value="{{ $employee->id }}" />
					<p class="editOption4 iskorišteno" style="display:none;">
					<input type="hidden" value="{{$razmjeranGO_PG - $daniZahtjevi_PG + $razmjeranGO - $daniZahtjevi }}" name="Dani" />
					@if($razmjeranGO - $daniZahtjevi > 0)
						Neiskorišteno {{ $razmjeranGO_PG - $daniZahtjevi_PG + $razmjeranGO - $daniZahtjevi }} dana razmjernog godišnjeg odmora 
					@else
						Svi dani godišnjeg odmora su iskorišteni! <br>
						Nemoguće je poslati zahtjev za godišnji odmor.
					@endif
					</p>
					<p class="editOption5 iskorišteno" style="display:none;">
						@if( ($slobodni_dani -  $koristeni_slobodni_dani) > 0)
							Neiskorišteno {{ $slobodni_dani }} slobodnih dana
						@else
							Svi slobodni dani su iskorišteni! <br>
							Nemoguće je poslati zahtjev za slobodni dan.
						@endif
					</p>

					<div class="datum form-group editOption1" >
						<input name="GOpocetak" class="date form-control" type="text" value = "{{  date('d-m-Y', strtotime($vacationRequest->GOpocetak)) }}"><i class="far fa-calendar-alt"></i>
						{!! ($errors->has('GOpocetak') ? $errors->first('GOpocetak', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<span class="editOption2 do" {!! ($vacationRequest->zahtjev != 'GO' ? ' style="display:none;" ': '') !!}>do</span>
					<div class="datum form-group editOption2" {!! ($vacationRequest->zahtjev != 'GO' ? ' style="display:none;" ': '') !!}>
						<input name="GOzavršetak" class="date form-control" type="text" value = "{{  date('d-m-Y', strtotime($vacationRequest->GOzavršetak)) }}"><i class="far fa-calendar-alt"></i>
						{!! ($errors->has('GOzavršetak') ? $errors->first('GOzavršetak', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="datum2 form-group editOption3" {!! ($vacationRequest->zahtjev == 'GO' ? ' style="display:none;" ': '') !!}>
						<span>od</span><input type="time" name="vrijeme_od" class="vrijeme" value={!! !$vacationRequest->vrijeme_od ? '08:00' : $vacationRequest->vrijeme_od !!} >
						<span>do</span><input type="time" name="vrijeme_do" class="vrijeme" value={!! !$vacationRequest->vrijeme_do ? '16:00' : $vacationRequest->vrijeme_do !!} }}" >
					</div>
					<div class="form-group padd_10 { ($errors->has('sprema')) ? 'has-error' : '' }}" style="clear:left">
						<label>Napomena:</label>
						<textarea rows="4" name="napomena" type="text" class="form-control">{{ $vacationRequest->napomena }}</textarea>
					</div>
					@if (Sentinel::inRole('administrator'))
						<div class="form-group">
							<label for="email">Slanje emaila:</label>
							<input type="radio" name="email" value="DA" checked> Poslati e-mail<br>
							<input type="radio" name="email" value="NE"> Ne slati mail
							
						</div>
					@endif
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-block" type="submit" value="Ispravi zahtjev" id="stil1">
				</form>
			</div>
		</div>
		<!--<div class="uputa">
			<p>*** Napomena:</p>
			<p>Sukladno radnopravnim propisima RH:<br>
				- radnik ima za svaku kalendarsku godinu pravo na godišnji odmor od najmanje 20 radnih dana,<br>
				- radnik ima pravo na dodatne dane godišnjeg odmora (po 1 radni dan za svakih navršenih četiri godina <br>radnog staža; po 2 radna dana radniku roditelju s dvoje ili više djece do 7 godina života),<br>
				- ukupno trajanje godišnjeg odmora radnika ne može iznositi više od 25 radnih dana.<br>
				- razmjerni dio godišnjeg odmora za tekuću godinu utvrđuje se u trajanju od 1/12 godišnjeg odmora za <br>svaki mjesec trajanja radnog odnosa u Duplicu u tekućoj godini.<br>

			Za eventualna pitanja, molimo kontaktirati pravni odjel na pravni@duplico.hr.<br>
			</p>
		</div>-->

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
					document.getElementById("zahtjev").innerHTML = "Zahtjev";
				}else {
					document.getElementById("napomena").value = "" ;
				}
				if(document.getElementById("prikaz").value == "Bolovanje" ){
					document.getElementById("zahtjev").innerHTML = "Obavijest";
				}
				if(document.getElementById("prikaz").value == "Izlazak"){
					document.getElementById("zahtjev").innerHTML = "Zahtjev";
				}
				if(document.getElementById("prikaz").value == "NPL" ||document.getElementById("prikaz").value == "PL"){
					document.getElementById("zahtjev").innerHTML = "Zahtjev";
				}
				if(document.getElementById("prikaz").value == "SLD"){
					document.getElementById("zahtjev").innerHTML = "Zahtjev";
				}
				if(document.getElementById("prikaz").value == "Vik"){
					document.getElementById("zahtjev").innerHTML = "Zahtjev";
				}
				
			}
		</script>
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
			  $('.text1').show();
			  $('.text2').hide();
			}
			if(selected == "editable4" || selected == "editable5"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption4').hide();
			  $('.text1').show();
			  $('.text2').hide();
			}
			if(selected == "editable6" || selected == "editable7"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption5').hide();
			  $('.editOption4').hide();
			  $('.text1').show();
			  $('.text2').hide();
			}
			if(selected == "editable5"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption5').show();
			  $('.text1').show();
			  $('.text2').hide();
			}
			
			if(selected == "editable3"){
			  $('.editOption1').show();
			  $('.editOption2').hide();
			  $('.editOption3').show();
			  $('.editOption4').hide();
			  $('.text1').show();
			  $('.text2').hide();
			}
			if(selected == "editable2"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption4').hide();
			  $('.text1').hide();
			  $('.text2').show();
			}
			});
		</script>
@stop

