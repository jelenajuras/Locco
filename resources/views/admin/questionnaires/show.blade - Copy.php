@extends('layouts.admin')

@section('title', 'Ankete')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}" type="text/css" >
@section('content')
<div class="row">
	<h2>Anketa {{ $questionnaire->naziv }}</h2>
	<div class="anketa">
		<div class="zaposlenik">
			<div class="dl_list">
				<dl>
					@foreach($evaluatingRatings as $evaluatingRating)
						<dt>{{ $evaluatingRating->rating }}</dt>
						<dd>{{ $evaluatingRating->naziv }}</dd><br>
					@endforeach
				</dl>
			</div>
		</div>
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.evaluations.store') }}">
			<div class="ime form-group">
				<h4>Ime zaposlenika
				<select name="ev_employee_id" class="ev_employee_id form-control" id="ev_employee_id1" required onchange="display_Anketa()" >
					<option value="" disabled selected ></option>
					@foreach($evaluatingEmployees as $evaluatingEmployee)
						<option value="{{ $evaluatingEmployee->ev_employee_id }}">{{ $evaluatingEmployee->evaleated_employee['first_name'] . ' ' . $evaluatingEmployee->evaleated_employee['last_name'] }}</option>
					@endforeach
				</select></h4>
			</div>
			<div class="ime form-group" id="tip_ankete1" hidden >
				<h4>Prikaz ankete
				<select name="tip_ankete" class="tip_ankete form-control" id="tip_ankete" required onchange="display_Tip()">
					<option value="" disabled selected ></option>
					<option value="grupa">Grupirano</option>
					<option value="podgrupa">Pojedinačno</option>
				</select></h4>
			</div>
			
			<p id="user" hidden >{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name  }}</p>
			<input name="employee_id" type="hidden" id="employee_id" value ="{{ $employee->id }}">
			<input name="questionnaire_id" type="hidden" value ="{{ $questionnaire->id }}">
			<input name="datum" type="hidden" value ="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
			
			<section class="pitanja">
				<div class="display_none" id="anketa_1">
					@foreach($evaluatingGroups as $evaluatingGroup)
						<details open>
							<summary id="{{ $evaluatingGroup->naziv }}">{{ $evaluatingGroup->naziv }}</summary>
							@foreach( $evaluatingQuestion->where('group_id', $evaluatingGroup->id) as $question )
								<div class="pitanje">
									<input name="question_id[{{ $question->id }}]" type="hidden" value="{{ $question->id }}"  id="group_id1" />
									<b>{{ $question->naziv }} </b> 
									<span class="ocj">							
										@foreach($evaluatingRatings as $evaluatingRating)
											<input type="radio" name="rating[{{ $question->id }}]" value="{{ $evaluatingRating->rating }}" id="myRadio1{{ $question->id }}"  /><b>{{ $evaluatingRating->rating }}</b>
										@endforeach 
									</span>
									<p class="opis">{{ $question->opis }}<p>
								</div>
							@endforeach
						</details>
					@endforeach
				</div>
				<div class="display_none" id="anketa_2">
					@foreach($evaluatingGroups as $evaluatingGroup)
						<details open>
							<summary id="{{ $evaluatingGroup->naziv }}">{{ $evaluatingGroup->naziv }}</summary>
								<input name="group_id[{{ $evaluatingGroup->id }}]" type="hidden" value="{{ $evaluatingGroup->id }}" id="group_id2" />
								<span class="ocj">									
									@foreach($evaluatingRatings as $evaluatingRating)
										<input type="radio" name="rating[{{ $evaluatingGroup->id }}]" value="{{ $evaluatingRating->rating }}" id="myRadio2{{ $evaluatingGroup->id }}"  /><b>{{ $evaluatingRating->rating }}</b>
									@endforeach 
								</span>
							@foreach( $evaluatingQuestion->where('group_id', $evaluatingGroup->id) as $question )
								
								<div class="pitanje">
									<b>{{ $question->naziv }} </b> 
									<p class="opis">{{ $question->opis }}<p>
								</div>
							@endforeach
						</details>
					@endforeach
				</div>
			</section>
			<input name="_token" value="{{ csrf_token() }}" type="hidden">
			<input class="btn btn-lg btn-primary" type="submit" value="Upiši" id="stil1">
		</form>
	</div>
</div>
<script>
$( document ).ready(function() {
	var ev_employee_id1 = $("#ev_employee_id1").val();
	var employee_id = $("#employee_id").val();
	var tip_ankete = $("#tip_ankete");
		
		
	
	
		console.log(ev_employee_id1);
		console.log(employee_id);
		
});

</script>


<script>

	$(document).ready(function(){
		if($("#user").text() == "Željko Rendulić"){
			console.log($("#user").text());
			$("#tip_ankete1").removeAttr("hidden");
		}
	});

	function display_Anketa() {
		var ev_employee_id1 = document.getElementById("ev_employee_id1").value;
		var employee_id = document.getElementById("employee_id").value;
		var tip_ankete = document.getElementById("tip_ankete");
		
		if(ev_employee_id1 === employee_id){
			document.getElementById("anketa_1").removeAttribute("class");
			document.getElementById("anketa_2").setAttribute("class", "display_none");
			document.getElementById("group_id2").setAttribute("class", "display_none");

			document.getElementById("tip_ankete").value = 'podgrupa';
			
		} else {
			
			document.getElementById("anketa_2").removeAttribute("class");
			document.getElementById("anketa_1").setAttribute("class", "display_none");
			document.getElementById("group_id1").setAttribute("class", "display_none");
			document.getElementById("tip_ankete").value = 'grupa';
		}
			
		
	}
	
	function display_Tip() {
		var tip_ankete = document.getElementById("tip_ankete").value;
		console.log(tip_ankete);
		if(tip_ankete == 'grupa'){
			document.getElementById("anketa_2").removeAttribute("class");
			document.getElementById("anketa_1").setAttribute("class", "display_none");
			document.getElementById("myRadio2").required  = true;
			document.getElementById("myRadio1").required  = false;
			document.getElementById("group_id1").setAttribute("class", "display_none");
		} else {
			document.getElementById("anketa_1").removeAttribute("class");
			document.getElementById("anketa_2").setAttribute("class", "display_none");
			document.getElementById("myRadio1").required  = true;
			document.getElementById("myRadio2").required  = false;
			document.getElementById("group_id2").setAttribute("class", "display_none");
		}
	}
</script>

@stop