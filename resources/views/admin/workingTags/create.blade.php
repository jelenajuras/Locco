@extends('layouts.admin')

@section('title', 'Oznaka radnog vremena')

@section('content')

<div class="row">
</br>
</br>
</br>
</br>
	<h1>Upis oznake radnog vremena</h1>
</div> 
<div class="container">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.workingTags.store') }}">
				 
					<div class="form-group {{ ($errors->has('naziv')) ? 'has-error' : '' }}">
						<label>Oznaka radnog vremena:</label>
						<input name="naziv" type="text" class="form-control" value="{{ old('naziv') }}" autofocus >
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('sati')) ? 'has-error' : '' }}">
						<label>Broj sati:</label>
						<input name="sati" type="numeric" class="form-control" value="{{ old('sati') }}">
						{!! ($errors->has('sati') ? $errors->first('sati', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop