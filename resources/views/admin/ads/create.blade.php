@extends('layouts.admin')

@section('title', 'Novi oglas')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Novi oglas</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" enctype="multipart/form-data" action="{{ route('admin.ads.store') }}">
					<div class="form-group {{ ($errors->has('subject'))  ? 'has-error' : '' }}">
                        <label>Naslov</label>
						<input name="subject" type="text" class="form-control" value="{{ old('subject') }}" required >
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
						<label>Obavijest:</label>
						<textarea id="summernote" name="description" required></textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input name="category_id" value="{{ $category_id }}" type="hidden">
					<div class="form-group {{ ($errors->has('price'))  ? 'has-error' : '' }}">
                        <label>Cijena</label>
						<input name="price" type="text" class="form-control" value="{{ old('price') }}" required hidden >
						{!! ($errors->has('price') ? $errors->first('price', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/summernote.js') }}"></script>
@stop

