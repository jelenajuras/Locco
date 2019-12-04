@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class"row">
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-4 col-lg-offset-4">
	<h2 id="zahtjev">Dodaj događaj</h2>
		<div class="panel-body">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.events.store') }}">
			<div class="form-group {{ ($errors->has('type')) ? 'has-error' : '' }}">
				<label>Tip događaja</label>
				<input name="type" list="type" class="form-control" maxlength="10" title="Slobodan unos maksimalno 10 znaka" required>
				<datalist id="type">
					<option value="Školovanje">
					<option value="Sastanak">
					<option value="Događaj">
				</datalist>
			</div>
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label>Naslov</label>
				<input name="title" type="text" class="form-control" maxlength="50" required>
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<label class="time_label">Datum</label>
			<div class="form-group input_date {{ ($errors->has('prezime')) ? 'has-error' : '' }}">
				<input name="date1" type="date" class="form-control" value="{!! isset($date) ? $date : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
				{!! ($errors->has('prezime') ? $errors->first('prezime', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group span">
					<span>do</span>
				</div>
			<div class="form-group input_date {{ ($errors->has('prezime')) ? 'has-error' : '' }}">
				<input name="date2" type="date" class="form-control" value="{!! isset($date) ? $date : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
				{!! ($errors->has('prezime') ? $errors->first('prezime', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		
				<label class="time_label">Vrijeme</label>
				<div class="form-group time {{ ($errors->has('time1')) ? 'has-error' : '' }}">
					<input name="time1" class="form-control" type="time" value="08:00:00" required />
					{!! ($errors->has('time1') ? $errors->first('time1', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group span">
					<span>do</span>
				</div>
				<div class="form-group time {{ ($errors->has('time2')) ? 'has-error' : '' }}">
					<input name="time2" class="form-control" type="time" value="08:00:00" required />
					{!! ($errors->has('time2') ? $errors->first('time2', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			
			<div class="form-group description clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label>Opis</label>
				<textarea name="description" class="form-control" type="text" required ></textarea>
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Spremi" id="stil1">
		</form>
	</div>
</div>
</div>
@stop