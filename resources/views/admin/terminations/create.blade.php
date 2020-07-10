@extends('layouts.admin')

@section('title', 'Nova vrsta otkaza')

@section('content')
<div class="page-header">
  <h2>Upis nove vrste otkaza</h2>
</div> 
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.terminations.store') }}">
				 
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
						<label>Naziv</label>
						<input name="naziv" type="text" class="form-control" autofocus>
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
					<input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši otkaz" id="stil1">
				</form>
			</div>
		</div>
	</div>
@stop