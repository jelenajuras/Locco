@extends('layouts.admin')

@section('title', 'Dodjela zaposlenika')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}" type="text/css" >

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Dodjela zaposlenika za ocjenjivanje</h2>
</div> 
<div class="row">
	<div class="col-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.evaluating_employees.store') }}">
					<h3 class="empl" >{{ $employee->employee['first_name'] . ' ' . $employee->employee['last_name'] }}</h3>	
					<input type="hidden" name="employee_id" value="{{ $employee->employee_id}}" />
					<div class="box form-group ">
						@foreach($registrations as $registration)
							@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
								<span class="ev_empl"><input type="checkbox" name="ev_employee_id[]" value="{{ $registration->employee_id }}" />{{ $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] }}</span>
							@endif
						@endforeach
					</div>	
					<div class="box form-group {{ ($errors->has('questionnaire_id'))  ? 'has-error' : '' }}">
						<label class="">Anketa</label>
						<select name="questionnaire_id" class="form-control">
							<option disabled selected></option>
							@foreach($questionnaires as $questionnaire)
								<option value="{{ $questionnaire->id }}">{{ $questionnaire->naziv }}</option>
							@endforeach
						</select>
						{!! ($errors->has('questionnaire_id') ? $errors->first('questionnaire_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="box form-group {{ ($errors->has('mjesec_godina'))  ? 'has-error' : '' }}">
						<label class="">Anketa za mjesec</label>
						<input class="date-own" type="month" name="mjesec_godina" id="mjesec" placeholder="Izbor mjeseca"/>
						<i class="far fa-calendar-alt" ></i>
						{!! ($errors->has('mjesec_godina') ? $errors->first('mjesec_godina', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					<input class="btn_align" type="submit" value="&#10004" title="Snimi promjenu" >
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.date-own1').datepicker({
		minViewMode: 1,
		format: 'm-yyyy'
	});
</script>
@stop