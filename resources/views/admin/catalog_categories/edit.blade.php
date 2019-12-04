@extends('layouts.admin')

@section('title', 'Ispravak kategorije')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Ispravak kategorije</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" enctype="multipart/form-data" action="{{ route('admin.catalog_categories.update', $catalog_category->id) }}">
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
                        <label>Naziv</label>
						<input name="name" type="text" class="form-control" value="{{ $catalog_category->name }}" required >
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
                        <label>Opis</label>
						<textarea name="description" type="text" rows="4" class="form-control">{{ $catalog_category->description }}</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
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