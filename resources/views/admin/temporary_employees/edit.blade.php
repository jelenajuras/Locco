@extends('layouts.admin')

@section('title', 'Ispravak radnika')
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
  <h2>Ispravak radnika</h2>
</div> 
<div class="">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.temporary_employees.update', $temporaryEmployee->id) }}">
					<div class="form-group {{ ($errors->has('first_name'))  ? 'has-error' : '' }}"">
						<label>Ime</label>						
						<input name="first_name" type="text" class="form-control" value="{{ $temporaryEmployee->first_name }}"  maxlength="50" required>
						{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('last_name'))  ? 'has-error' : '' }}"">
						<label>Prezime</label>						
						<input name="last_name" type="text" class="form-control" value="{{ $temporaryEmployee->last_name }}"  maxlength="50"  required>
						{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('radnoMjesto_id'))  ? 'has-error' : '' }}">
						<label>Radno mjesto:</label>
						<select class="form-control" name="radnoMjesto_id" id="sel1" required>
							<option selected disabled ></option>
							@foreach($works as $work)
								<option name="radnoMjesto_id" value="{{ $work->id }}" {!! $temporaryEmployee->radnoMjesto_id == $work->id ? 'selected' : '' !!}>{{ $work->odjel . ' - '. $work->naziv }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('radnoMjesto_id') ? $errors->first('radnoMjesto_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>	
					<div class="form-group {{ ($errors->has('superior_id'))  ? 'has-error' : '' }}">
						<label>Nadređeni djelatnik:</label>
						<select class="form-control" name="superior_id" id="sel1">
							<option selected value="0"></option>
							@foreach($employees as $employee)
								<option name="superior_id" value="{{ $employee->id }}" {!! $temporaryEmployee->superior_id == $employee->id ? 'selected' : '' !!}>{{ $employee->last_name . ' '. $employee->first_name }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('superior_id') ? $errors->first('superior_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<span><b>Datum prijave:</b></span>
						<input name="datum_prijave" class="date form-control" type="date" value ="{{ $temporaryEmployee->datum_prijave }}" required>
						{!! ($errors->has('datum_prijave') ? $errors->first('datum_prijave', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Ime oca</label>
						<input name="ime_oca" type="text" class="form-control" value="{{ $temporaryEmployee->ime_oca }}">
					</div>
					<div class="form-group">
						<label>Ime majke</label>
						<input name="ime_majke" type="text" class="form-control" value="{{ $temporaryEmployee->ime_majke }}">
					</div>
					<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
						<label>OIB</label>
						<input name="oib" type="text" class="form-control" value="{{ $temporaryEmployee->oib }}">
						{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					<div class="form-group {{ ($errors->has('oi')) ? 'has-error' : '' }}">
						<label>Broj osobne iskaznice:</label>
						<input name="oi" type="text" class="form-control" value="{{ $temporaryEmployee->oi }}">
						{!! ($errors->has('oi') ? $errors->first('oi', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('oi_istek')) ? 'has-error' : '' }}">
						<label>Datum isteka OI: </label>
						<input name="oi_istek" class="date form-control" type="date" value = "{{ date('Y-m-d', strtotime($temporaryEmployee->oi_istek)) }}">
						{!! ($errors->has('oi_istek') ? $errors->first('oi_istek', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('datum_rodjenja')) ? 'has-error' : '' }}">
						<label>Datum rođenja</label>
						<input name="datum_rodjenja" class="date form-control" type="date" value ="{{ date('Y-m-d', strtotime($temporaryEmployee->datum_rodjenja)) }}">
						{!! ($errors->has('datum_rodjenja') ? $errors->first('datum_rodjenja', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Mjesto rođenja</label>
						<input name="mjesto_rodjenja" type="text" class="form-control" value="{{ $temporaryEmployee->mjesto_rodjenja }}">
					</div>
					<div class="form-group">
						<label>	Mobitel</label>
						<input name="mobitel" type="text" class="form-control" value="{{ $temporaryEmployee->mobitel }}">
					</div>
					<div class="form-group">
						<label>Privatan mobitel</label>
						<input name="priv_mobitel" type="text" class="form-control" value="{{ $temporaryEmployee->priv_mobitel }}">
					</div>
					<div class="form-group">
						<label>E-mail</label>
						<input name="email" type="text" class="form-control" value="{{ $temporaryEmployee->email }}">
					</div>
					<div class="form-group">
						<label>Privatan e-mail</label>
						<input name="priv_email" type="text" class="form-control" value="{{ $temporaryEmployee->priv_email }}">
					</div>
					<div class="form-group {{ ($errors->has('prebivaliste_adresa')) ? 'has-error' : '' }}">
						<label>Prebivalište - adresa:</label>
						<input name="prebivaliste_adresa" type="text" class="form-control" value="{{ $temporaryEmployee->prebivaliste_adresa }}">
					</div>
					{!! ($errors->has('prebivaliste_adresa') ? $errors->first('prebivaliste_adresa', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('prebivaliste_grad')) ? 'has-error' : '' }}">
						<label>Prebivalište - grad:</label>
						<input name="prebivaliste_grad" type="text" class="form-control" value="{{ $temporaryEmployee->prebivaliste_grad }}">
					</div>
					{!! ($errors->has('prebivaliste_grad') ? $errors->first('prebivaliste_grad', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('zvanje')) ? 'has-error' : '' }}">
						<label>Zvanje:</label>
						<input name="zvanje" type="text" class="form-control" value="{{ $temporaryEmployee->zvanje }}">
					</div>
					{!! ($errors->has('zvanje') ? $errors->first('zvanje', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('sprema')) ? 'has-error' : '' }}">
						<label>Stručna sprema:</label>
						<input name="sprema" type="text" class="form-control" value="{{ $temporaryEmployee->sprema }}">
					</div>
					{!! ($errors->has('sprema') ? $errors->first('sprema', '<p class="text-danger">:message</p>') : '') !!}
					<div class="form-group {{ ($errors->has('bracno_stanje')) ? 'has-error' : '' }}">
						<label>Bračno stanje:</label>
						<select class="form-control" name="bracno_stanje">
							<option {!! ($temporaryEmployee->bracno_stanje == 'U braku' ? 'selected ': '') !!}  >U braku</option>
							<option {!! ($temporaryEmployee->bracno_stanje == 'nije u braku' ? 'selected' : '') !!} >nije u braku</option>
						</select>
						{!! ($errors->has('bracno_stanje') ? $errors->first('bracno_stanje', '<p class="text-danger">:message</p>') : '') !!}
					</div>				
					<div class="form-group">
						<label>Konfekcijski broj</label>
						<input name="konf_velicina" type="text" class="form-control" value="{{ $temporaryEmployee->konf_velicina }}">
					</div>
					<div class="form-group">
					<label>Veličina cipela</label>
						<input name="broj_cipela" type="text" class="form-control" value="{{ $temporaryEmployee->broj_cipela }}">
					</div>			
					<div class="form-group">
						<label>Napomena: </label>
						<textarea class="form-control" name="napomena" maxlength="255">{{ $temporaryEmployee->napomena }}</textarea>
					</div>
					<div class="form-group">
						<input type="checkbox" name="odjava" value="1" id="odjava" {!! $temporaryEmployee->odjava == 1 ? 'checked' : '' !!}> <label for="odjava">Djelatnik je odjavljen</label>
					</div>				
					{{ csrf_field() }}
					{{ method_field('PUT') }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi radnika" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
@stop