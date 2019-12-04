@extends('layouts.admin')

@section('title', 'Nova proizvođač')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Upis novog proizvođača</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" enctype="multipart/form-data" action="{{ route('admin.catalog_manufacturers.store') }}">
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
                        <label>Naziv</label>
						<input name="name" type="text" class="form-control" value="{{ old('name') }}" required >
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
                        <label>Opis</label>
						<textarea name="description" type="text" rows="4" class="form-control" >{{ old('description') }}</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('url'))  ? 'has-error' : '' }}">
                        <label>URL</label>
						<input name="url" type="url" class="form-control" value="{{ old('url') }}" required>
						{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('email'))  ? 'has-error' : '' }}">
						<label>E-mail adresa</label>
						<input name="email" type="email" class="form-control" value="{{ old('email') }}">
						{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('phone'))  ? 'has-error' : '' }}">
						<label>Telefon</label>
						<input name="phone" type="text" class="form-control" value="{{ old('phone') }}">
						{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input name="category_id" type="hidden" class="form-control" value="{{ $category_id }}" required >
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/summernote_no pict.js') }}"></script>
@stop