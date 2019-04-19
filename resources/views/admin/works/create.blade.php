@extends('layouts.admin')

@section('title', 'Novo radno mjesto')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')

<div class="page-header">
  <h2>Upis novog radnog mjesta</h2>
</div> 
<div class="">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.works.store') }}">
				 
					<div class="form-group {{ ($errors->has('odjel'))  ? 'has-error' : '' }}">
                        <label>Odjel</label>
						<select class="form-control" name="odjel" id="sel1" value="{{ old('odjel') }}">
							<option name="odjel">{{ 'Zajednički poslovi' }}</option>
							<option name="odjel">{{ 'Odjel informatičkih tehnologija' }}</option>
							<option name="odjel">{{ 'Inženjering'  }}</option>
						</select>
						{!! ($errors->has('odjel') ? $errors->first('odjel', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('naziv')) ? 'has-error' : '' }}">
						<label>Naziv radnog mjesta:</label>
						<input name="naziv" type="text" class="form-control" value="{{ old('naziv') }}" required>
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Opis rada:</label>
						<input name="job_description" type="text" class="form-control" value="{{ old('job_description') }}" required>
					</div>
					<div class="form-group">
						<label>Pravilnik:</label>
						<select class="form-control" name="pravilnik" id="sel1" value="{{ old('pravilnik') }}">
							<option selected="selected" value=""></option>
							<option name="pravilnik">{{ 'Pravilnik o poslovima s posebnim uvjetima rada, čl. 3.' }}</option>
							<option name="pravilnik">{{ 'Pravilnik o sigurnosti i zaštiti zdravlja pri radu sa računalom' }}</option>
						</select>
					</div>
					<div class="form-group">
						<label>Prema točkama:</label>
						<input name="tocke" type="text" class="form-control" value="{{ old('tocke') }}">
					</div>
					<div class="form-group {{ ($errors->has('user_id'))  ? 'has-error' : '' }}">
                        <label>Nadređen djelatnik</label>
						<select class="form-control" name="user_id" id="sel1" value="{{ old('user_id') }}"required>
							<option selected="selected"></option>
						@foreach($users as $user)
							@if(! $terminations->where('employee_id', $user->employee_id)->first())
								<option name="user_id" value="{{ $user->employee_id }}">{{ $user->employee['last_name'] . ' ' . $user->employee['first_name'] }} </option>
							@endif
						@endforeach
						</select>
						{!! ($errors->has('user_id') ? $errors->first('user_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
                        <label>Prvi nadređen djelatnik</label>
						<select class="form-control" name="prvi_userId" id="sel1" value="{{ old('prvi_userId') }}" >
							<option selected="selected"></option>
						@foreach($users as $user)
							@if(! $terminations->where('employee_id', $user->employee_id)->first())
								<option name="prvi_userId" value="{{ $user->employee_id }}">{{ $user->employee['last_name'] . ' ' . $user->employee['first_name'] }}</option>
							@endif
						@endforeach
						</select>
					</div>
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši radno mjesto" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
@stop