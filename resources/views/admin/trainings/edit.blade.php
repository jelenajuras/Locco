@extends('layouts.admin')

@section('title', 'Novo osposobljavanje')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Upis novog osposobljavanja</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.trainings.update', $training->id) }}">
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
                        <label>Naziv</label>
						<input name="name" type="text" class="form-control" maxlength="100" value="{{  $training->name }}" required >
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
                        <label>Opis</label>
						<input name="description" type="text" class="form-control" maxlength="200" value="{{  $training->description }}" required >
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="aktivna form-group {{ ($errors->has('institution'))  ? 'has-error' : '' }}">
						<label>Učilište</label>
						<input name="institution" type="text" class="form-control" maxlength="100" value="{{  $training->institution }}" required >
						{!! ($errors->has('institution') ? $errors->first('institution', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop