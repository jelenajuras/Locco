@extends('layouts.admin')

@section('title', 'Ispravak radne opreme')

@section('content')

<div class="row">
</br>
</br>
</br>
</br>
  <h1>Ispravak podataka radne opreme</h1>
</div> 
<div class="container">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.equipments.update', $equipment->id) }}">
				 
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Naziv</label>
						<input name="naziv" type="text" class="form-control" value="{{ $equipment->naziv }}"autofocus>
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
                        <label>Napomena</label>
						<input name="napomena" type="text" class="form-control" value="{{ $equipment->napomena }}">
					</div>
					<div class="form-group {{ ($errors->has('količina_monter'))  ? 'has-error' : '' }}">
                        <label>Količina (monteri)</label>
						<input name="količina_monter" type="text" class="form-control" value="{{ $equipment->količina_monter }}">
						{!! ($errors->has('količina_monter') ? $errors->first('količina_monter', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('količina_inženjer'))  ? 'has-error' : '' }}">
                        <label>Količina (inženjeri)</label>
						<input name="količina_inženjer" type="text" class="form-control" value="{{ $equipment->količina_inženjer }}">
						{!! ($errors->has('količina_inženjer') ? $errors->first('količina_inženjer', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('User_id'))  ? 'has-error' : '' }}"">
                        <label>Zadužen djelatnik</label>
						<select class="form-control" name="User_id" id="sel1">
							<option selected="selected" name="User_id" value="{{ $equipment->User_id }}">{{ $equipment->user['first_name'] . ' ' . $equipment->user['last_name'] }}</option>
						@foreach(DB::table('users')->orderBy('last_name','ASC')->get() as $user)
							<option name="User_id" value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
						@endforeach
						</select>
						{!! ($errors->has('User_id') ? $errors->first('User_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši opremu" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop