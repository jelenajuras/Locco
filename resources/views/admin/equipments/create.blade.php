@extends('layouts.admin')

@section('title', 'Nova radna oprema')

@section('content')
<div class="page-header">
  <h2>Upis nove radne opreme</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.equipments.store') }}">
				 
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Naziv</label>
						<input name="naziv" type="text" class="form-control" value="{{ old('naziv') }}"autofocus>
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
                        <label>Napomena</label>
						<input name="napomena" type="text" class="form-control" value="{{ old('napomena') }}">
					</div>
					<div class="form-group {{ ($errors->has('količina_monter'))  ? 'has-error' : '' }}">
                        <label>Količina (monteri)</label>
						<input name="količina_monter" type="text" class="form-control" value="{{ old('količina_monter') }}">
						{!! ($errors->has('količina_monter') ? $errors->first('količina_monter', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('količina_inženjer'))  ? 'has-error' : '' }}">
                        <label>Količina (inženjeri)</label>
						<input name="količina_inženjer" type="text" class="form-control" value="{{ old('količina_inženjer') }}">
						{!! ($errors->has('količina_inženjer') ? $errors->first('količina_inženjer', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('User_id'))  ? 'has-error' : '' }}">
                        <label>Zadužen djelatnik</label>
						<select class="form-control" name="User_id" id="sel1" value="{{ old('User_id') }}">
							<option selected="selected"></option>
						@foreach(DB::table('users')->orderBy('last_name','ASC')->get() as $user)
							<option name="User_id" value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
						@endforeach
						</select>
						{!! ($errors->has('User_id') ? $errors->first('User_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši opremu" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop