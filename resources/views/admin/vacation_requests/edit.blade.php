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
					</select> 
					<input name="employee_id" type="hidden" value="{{ $employee->id }}" />
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
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-block" type="submit" value="Ispravi zahtjev" id="stil1">
				</form>
			</div>
		</div>
		
		<script type="text/javascript">
			$('.date').datepicker({  
			   format: 'dd-mm-yyyy',
			   startDate:'-60y',
			   endDate:'+1y',
			}); 
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
			}
			if(selected == "editable2"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			}
			if(selected == "editable3"){
			  $('.editOption1').show();
			  $('.editOption3').show();
			  $('.editOption2').hide();
			}
			if(selected == "editable4"){
			  $('.editOption1').show();
			  $('.editOption2').show();
			  $('.editOption3').hide();
			  $('.editOption4').hide();
			}
			});

		</script>
		<script>
			function myFunction() {
				alert("Page is loaded");
			}
		</script>
@stop

