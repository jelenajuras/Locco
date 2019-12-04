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
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.evaluations.update', $evaluatingEmployee->id) }}">
			<div class="ime form-group">
				<h4>Ime zaposlenika
				<select name="ev_employee_id" class="ev_employee_id form-control" id="ev_employee_id1" required >
					<option value="{{ $evaluatingEmployee->ev_employee_id }}">{{ $evaluatingEmployee->evaleated_employee['first_name'] . ' ' . $evaluatingEmployee->evaleated_employee['last_name']}}</option>
				</select></h4>
			</div>
			<div class="ime form-group" id="tip_ankete1" hidden >
				<h4>Prikaz ankete
				<select name="tip_ankete" class="tip_ankete form-control" id="tip_ankete" required>
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
									<b>{{ $question->naziv }}</b> 
									<span class="ocj">							
										@foreach($evaluatingRatings as $evaluatingRating1)
											<input type="radio" name="rating[{{ $question->id }}]" value="{{ $evaluatingRating1->rating }}" id="myRadio1{{ $question->id }}" required {!! $evaluations->where('group_id',$evaluatingGroup->id)->where('question_id',$question->id)->first()->rating === $evaluatingRating1->rating ? 'checked' : ''!!} /><b>{{ $evaluatingRating1->rating }}</b>
										@endforeach 
									</span>
									<p class="opis">{{ $question->opis }}<p>
								</div>
							@endforeach
						</details>
					@endforeach
				</div>
				<div class="display_none" id="anketa_2">
					@foreach($evaluatingGroups as $evaluatingGroup1)
						<details open>
							<summary id="{{ $evaluatingGroup1->naziv }}">{{ $evaluatingGroup1->naziv }}</summary>
								<input name="group_id[{{ $evaluatingGroup1->id }}]" type="hidden" value="{{ $evaluatingGroup1->id }}" id="group_id2" />
								<span class="ocj">									
									@foreach($evaluatingRatings as $evaluatingRating2)
										<input type="radio" name="rating[{{ $evaluatingGroup1->id }}]" value="{{ $evaluatingRating2->rating }}" id="myRadio2{{ $evaluatingGroup1->id }}" required {!!$evaluations->where('group_id',$evaluatingGroup1->id)->first()->rating  == $evaluatingRating2->rating ? 'checked' : ''!!}  /><b>{{ $evaluatingRating2->rating }} </b>
									@endforeach 
								</span>
							@foreach( $evaluatingQuestion->where('group_id', $evaluatingGroup1->id) as $question1 )
								
								<div class="pitanje">
									<b>{{ $question1->naziv }} </b> 
									<p class="opis">{{ $question1->opis }}<p>
								</div>
							@endforeach
						</details>
					@endforeach
				</div>
			</section>
			{{ method_field('PUT') }}
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary" type="submit" value="Upiši" id="stil1">
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		if($("#user").text() == "Željko Rendulić"){
			$("#tip_ankete1").removeAttr("hidden");
		}
	});
	
	$("#ev_employee_id1").change(function(){
		var radio1 = $( '#anketa_2 input[type=radio]' );
		var radio2 = $( '#anketa_1 input[type=radio]' );
		var anketa1 = $('#anketa_1');
		var anketa2 =  $('#anketa_2');
		var ev_employee_id1 = $("#ev_employee_id1").val();
		var employee_id = $("#employee_id").val();
		var tip_ankete = $("#tip_ankete");
		
		if(ev_employee_id1 === employee_id){
			tip_ankete.val('podgrupa');
			anketa1.removeAttr('class');
			anketa2.attr("class", "display_none");
			radio1.removeAttr("required");
			radio2.attr('required', 'required');
		}else {
			tip_ankete.val('grupa');
			radio1.attr('required', 'required');
			radio2.removeAttr("required");
			anketa1.attr("class", "display_none");
			anketa2.removeAttr('class');
		}
	});
	$("#tip_ankete").change(function(){
		var radio1 = $( '#anketa_2 input[type=radio]' );
		var radio2 = $( '#anketa_1 input[type=radio]' );
		var anketa1 = $('#anketa_1');
		var anketa2 =  $('#anketa_2');
		var tip_ankete = $("#tip_ankete").val();
		
		if(tip_ankete === 'podgrupa'){
			anketa1.removeAttr('class');
			anketa2.attr("class", "display_none");
			radio1.removeAttr("required");
			radio2.attr('required', 'required');
		}else {
			radio1.attr('required', 'required');
			radio2.removeAttr("required");
			anketa1.attr("class", "display_none");
			anketa2.removeAttr('class');
		}
	});
	
</script>

@stop