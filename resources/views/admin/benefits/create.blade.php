@extends('layouts.admin')

@section('title', 'Nova pogodnost')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Upis nove pogodnosti</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" enctype="multipart/form-data" action="{{ route('admin.benefits.store') }}">
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
                        <label>Naziv</label>
						<input name="name" type="text" class="form-control" value="{{ old('name') }}" required >
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
                        <label>Opis</label>
						<textarea name="description" type="text" rows="4" class="form-control" required>{{ old('description') }}</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
							 <label>Dodatni opis</label>
							<textarea id="summernote" name="comment" ></textarea>
                    </div>
					<div class="form-group {{ ($errors->has('url'))  ? 'has-error' : '' }}">
                        <label>URL</label>
						<input name="url" type="url" class="form-control" value="{{ old('url') }}">
						{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group">
                        <label>URL - letak</label>
						<input name="url2" type="url" class="form-control" value="{{ old('url2') }}">
                    </div>
					<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
                        <label>Status</label>
						<input type="radio" class="" name="status" value="neaktivna" checked />NEAKTIVNA 
						<input type="radio" class="" name="status" value="aktivna" />AKTIVNA
						{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/summernote_no pict.js') }}"></script>
@stop