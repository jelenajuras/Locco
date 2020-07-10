@extends('layouts.admin')

@section('title', 'Ispravak otkaza')

@section('content')
<div class="page-header">
  <h2>Upis nove vrste otkaza</h2>
</div> 

	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.terminations.update', $termination->id) }}">
				 
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Naziv</label>
						<input name="naziv" type="text" class="form-control" value="{{ $termination->naziv }}">
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi" id="stil1">
				</form>
			</div>
		</div>
	</div>


@stop