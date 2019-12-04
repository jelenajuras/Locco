@extends('layouts.admin')

@section('title', 'Prijava radnika')
<style>
.staž{
	display:inline-block;
	padding:5px;
	fort-size:0.75rem;
	border-radius: 5%;
	width: 50px;
}
</style>
@section('content')
<div class="page-header">
  <h2>Ispravak prijave radnika</h2>
</div> 
<div class="">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.registrations.update', $registration->id ) }}">
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
						<span><b>Ime i prezime:</b></span>
						<h3>{{ $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] }}</h3>
						<input name="employee_id" type="hidden" class="form-control" value="{{ $registration->employee_id }}" >
					</div>
					{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('radnoMjesto_id'))  ? 'has-error' : '' }}">
						<span><b>Radno mjesto:</b></span>
						<select class="form-control" name="radnoMjesto_id" id="sel1" value="{{ $registration->radnoMjesto_id }}">
							<option selected="selected" name="radnoMjesto_id" value="{{ $registration->radnoMjesto_id}}">{{ $registration->work['odjel'] . ' - ' . $registration->work['naziv'] }}</option>
							@foreach(DB::table('works')->orderBy('odjel','ASC')->orderBy('naziv','ASC')->get() as $work)
								<option name="radnoMjesto_id" value="{{ $work->id }}">{{ $work->odjel . ' - '. $work->naziv }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('radnoMjesto_id') ? $errors->first('radnoMjesto_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>	
					<div class="form-group {{ ($errors->has('superior_id'))  ? 'has-error' : '' }}">
						<span><b>Nadređeni djelatnik:</b></span>
						<select class="form-control" name="superior_id" id="sel1" >
							<option selected value="0"></option>
							@foreach($employees as $employee)
								<option name="superior_id" value="{{ $employee->id }}" {!! $employee->id ==  $registration->superior_id ? 'selected' : '' !!}>{{ $employee->last_name . ' '. $employee->first_name }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('superior_id') ? $errors->first('superior_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<span><b>Datum prijave:</b></span>
						<input name="datum_prijave" class="date form-control" type="date" value = "{{ date('Y-m-d', strtotime( $registration->datum_prijave)) }}">
						{!! ($errors->has('datum_prijave') ? $errors->first('datum_prijave', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('probni_rok'))  ? 'has-error' : '' }}">
						<span><b>Probni rok (dana):</b></span>
						<input name="probni_rok" type="text" class="form-control" value="{{ $registration->probni_rok }}">
					</div>
						{!! ($errors->has('probni_rok') ? $errors->first('probni_rok', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('staz'))  ? 'has-error' : '' }}">
 						<span><b>Staž kod prošlog poslodavca (broj godina-mjeseci-dana):</b></span><br>
						<input name="stazY" type="text" class="staž" value="{{ $stažY }}">-
						<input name="stazM" type="text" class="staž" value="{{ $stažM}}">-
						<input name="stazD" type="text" class="staž" value="{{ $stažD }}">
					</div>
					<div class="form-group {{ ($errors->has('lijecn_pregled'))  ? 'has-error' : '' }}">
						<label>Datum liječničkog pregleda: </label>
						<input name="lijecn_pregled" class="date form-control" type="date" value ="{{ date('Y-m-d', strtotime($registration->lijecn_pregled)) }}">
					</div>
					{!! ($errors->has('lijecn_pregled') ? $errors->first('lijecn_pregled', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('ZNR'))  ? 'has-error' : '' }}">
						<label>Datum obuke zaštite na radu: </label>
						<input name="ZNR" class="date form-control" type="date"  value ="{{ date('Y-m-d', strtotime($registration->ZNR)) }}">
					</div>
					{!! ($errors->has('ZNR') ? $errors->first('ZNR', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group">
						<input type="checkbox" name="prekidStaza" value="DA" {!! $registration->prekidStaza == 'DA' ? 'checked' : '' !!}> Prekid radnog odnosa više od 8 dana
					</div>
					<div class="form-group">
						<input type="checkbox" name="prvoZaposlenje" value="DA" {!! $registration->prvoZaposlenje == 'DA' ? 'checked' : '' !!}> Prvo zaposlenje
					</div>
					<div class="form-group">
						<label>Napomena: </label>
						<textarea class="form-control" name="napomena">{{ $registration->napomena }}</textarea>
					</div>
					<div class="form-group">
						<label>Obračun prekovremenih kao: </label>
						<select class="form-control" name="slDani" value="{{ $registration->slDani }}">
							<option name="slDani" value="0" {!! $registration->slDani == "" ? 'selected' : '' !!}></option>
							<option name="slDani" value="1" {!! $registration->slDani == "1" ? 'selected' : '' !!}>Slobodni dani</option>
							<option name="slDani" value="0" {!! $registration->slDani == "0" ? 'selected' : '' !!} >Isplata</option>
						</select>
					</div>
					<div class="form-group">
						<input type="checkbox" name="stranac" value="{!! $registration->stranac == '1'? 1 : 0 !!}" id="stranac" {!! $registration->stranac == '1'? 'checked' : '' !!}> <label for="stranac">Djelatnik je stranac</label>
					</div>
					<div class="form-group" hidden id="dozvola">
						<label>Datum isteka dozvole boravka u RH: </label>
						<input name="datum_dozvola" class="date form-control" type="date" value="{{ $registration->datum_dozvola }}">
					</div>
					{{ method_field('PUT') }}
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi podatke radnika" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$('#stranac').change(function(){
	$('#dozvola').toggle();

});
if($('#stranac').val() == 1 ){
	$('#dozvola').show();
} else {
	$('#dozvola').hide();
}
</script>
@stop