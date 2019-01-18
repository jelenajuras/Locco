@extends('layouts.admin')

@section('title', 'Novi kandidat')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
  <h2>Upis novog kandidata</h2>
</div> 
<?php 
	$job_interview_id = substr(URL::full(),strpos(URL::full(),'?')+1);
	if($job_interview_id != ''){
		$job_interview = $job_interviews->where('id', $job_interview_id)->first();
	}
?>
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employees.store') }}">
					<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
						<label>Ime:</label>
						<input name="first_name" type="text" class="form-control" value="{!! $job_interview_id ? $job_interview['first_name'] : old('first_name') !!}"
						autofocus>
						{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
						<label>Prezime:</label>
						<input name="last_name" type="text" class="form-control" value="{!! $job_interview_id ? $job_interview['last_name'] : '' !!}"">
						{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Ime oca: </label>
						<input name="ime_oca" type="text" class="form-control" value="{{ old('ime_oca') }}">
					</div>
					<div class="form-group">
						<label>Ime majke: </label>
						<input name="ime_majke" type="text" class="form-control" value="{{ old('ime_majke') }}">
					</div>
					<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
						<label>OIB: </label>
						<input name="oib" type="text" class="form-control" value="{!! $job_interview_id ? $job_interview['oib'] : old('oib') !!}"">
						{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					<div class="form-group {{ ($errors->has('oi')) ? 'has-error' : '' }}">
						<label>Broj osobne iskaznice: </label>
						<input name="oi" type="text" class="form-control" value="{{ old('oi') }}">
						{!! ($errors->has('oi') ? $errors->first('oi', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('oi_istek')) ? 'has-error' : '' }}">
						<label>Datum isteka OI: </label>
						<input name="oi_istek" class="date form-control" type="text" value = "{{ old('datum_rodjenja')}}">
						{!! ($errors->has('oi_istek') ? $errors->first('oi_istek', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('datum_rodjenja')) ? 'has-error' : '' }}">
						<label>Datum rođenja: </label>
						<input name="datum_rodjenja" class="date form-control" type="text" value = "{{ old('datum_rodjenja')}}">
						{!! ($errors->has('datum_rodjenja') ? $errors->first('datum_rodjenja', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Mjesto rođenja: </label>
						<input name="mjesto_rodjenja" type="text" class="form-control" value="{{ old('mjesto_rodjenja') }}">
					</div>
					<div class="form-group">
						<label>Mobitel: </label>
						<input name="mobitel" type="text" class="form-control" value="{{ old('mobitel') }}">
					</div>
					<div class="form-group">
						<label>Privatni mobitel: </label>
						<input name="priv_mobitel" type="text" class="form-control" value="{!! $job_interview_id ? $job_interview['telefon'] : old('priv_mobitel') !!}">
					</div>
					<div class="form-group">
						<label>E-mail: </label>
						<input name="email" type="text" class="form-control" value="{{ old('email') }}">
					</div>
					<div class="form-group">
						<label>Privatni e-mail: </label>
						<input name="priv_email" type="text" class="form-control" value="{!! $job_interview_id ? $job_interview['email'] : old('priv_email') !!}">
					</div>
					<div class="form-group {{ ($errors->has('prebivaliste_adresa')) ? 'has-error' : '' }}">
						<label>Prebivalište - adresa:</label>
						<input name="prebivaliste_adresa" type="text" class="form-control" value="{{ old('prebivaliste_adresa') }}">
						{!! ($errors->has('prebivaliste_adresa') ? $errors->first('prebivaliste_adresa', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('prebivaliste_grad')) ? 'has-error' : '' }}">
						<label>Prebivalište - grad:</label>
						<input name="prebivaliste_grad" type="text" class="form-control" value="{{ old('prebivaliste_grad') }}">
						{!! ($errors->has('prebivaliste_grad') ? $errors->first('prebivaliste_grad', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<?php 
						$i = 0;
					?>	
					<label role="button" data-toggle="collapse" href="#collapseExample{{$i}}" aria-expanded="false" aria-controls="collapseExample{{$i}}">Boravište nije isto kao prebivalište<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span></label>
					
					<div class="collapse" id="collapseExample{{$i}}">	
						<div class="form-group">
							<label>Boravište - adresa:</label>
							<input name="boraviste_adresa" type="text" class="form-control" value="{{ old('boraviste_adresa') }}">
						</div>	
						<div class="form-group">
							<label>Boravište - grad:</label>
							<input name="boraviste_grad" type="text" class="form-control" value="{{ old('boraviste_grad') }}">
						</div>				
					</div>

					<div class="form-group {{ ($errors->has('zvanje')) ? 'has-error' : '' }}">
						<label>Zvanje:</label>
						<input name="zvanje" type="text" class="form-control" value="{!! $job_interview_id ? $job_interview['zvanje'] : old('zvanje') !!}">
						{!! ($errors->has('zvanje') ? $errors->first('zvanje', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					<div class="form-group {{ ($errors->has('sprema')) ? 'has-error' : '' }}">
						<label>Stručna sprema:</label>
						<input name="sprema" type="text" class="form-control" value="{!! $job_interview_id ? $job_interview['sprema'] : old('sprema') !!}">
						{!! ($errors->has('sprema') ? $errors->first('sprema', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group  {{ ($errors->has('bracno_stanje'))  ? 'has-error' : '' }}">
						<label>Bračno stanje:</label>
						<select class="form-control" name="bracno_stanje" value="{{ old('bracno_stanje') }}">
							<option selected="selected" value=""></option>
							<option>U braku</option>
							<option>nije u braku</option>
						</select>
						{!! ($errors->has('bracno_stanje') ? $errors->first('bracno_stanje', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					<div class="form-group {{ ($errors->has('radnoMjesto_id'))  ? 'has-error' : '' }}">
                        <label>Radno mjesto:</label>
						<select class="form-control" name="radnoMjesto_id" id="sel1" value="{{ old('radnoMjesto_id') }}">
							<option selected="selected" value=""></option>@foreach(DB::table('works')->orderBy('odjel','ASC')->orderBy('naziv','ASC')->get() as $work)
								<option name="radnoMjesto_id" value="{{ $work->id }}">{{ $work->odjel . ' - '. $work->naziv }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('radnoMjesto_id') ? $errors->first('radnoMjesto_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					
					<div class="form-group {{ ($errors->has('lijecn_pregled'))  ? 'has-error' : '' }}">
						<label>Datum liječničkog pregleda: </label>
						<input name="lijecn_pregled" class="date form-control" type="text" value = "{{ old('lijecn_pregled')}}">
						{!! ($errors->has('lijecn_pregled') ? $errors->first('lijecn_pregled', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('ZNR'))  ? 'has-error' : '' }}">
						<label>Datum obuke zaštite na radu: </label>
						<input name="ZNR" class="date form-control" type="text" value = "{{ old('ZNR')}}">
						{!! ($errors->has('ZNR') ? $errors->first('ZNR', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Konfekcijski broj: </label>
						<input name="konf_velicina" type="text" class="form-control" value="{{ old('konf_velicina') }}">
					</div>
					<div class="form-group">
						<label>Broj cipela: </label>
						<input name="broj_cipela" type="text" class="form-control" value="{{ old('broj_cipela') }}">
					</div>
					<script type="text/javascript">
								$('.date').datepicker({  
								   format: 'dd-mm-yyyy',
								   startDate:'-60y',
								   endDate:'+10y',
								}); 
					</script> 
					<div class="form-group">
						<label>Napomena: </label>
						<textarea class="form-control" name="napomena"></textarea>
					</div>
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši kandidata" id="stil1">
				</form>

			</div>
		</div>
	</div>
</div>

@stop