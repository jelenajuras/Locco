@extends('layouts.admin')

@section('title', 'Isravak pogodnosti')

@section('content')
<div class="container-fluid">
	<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
	</a>
	<div class="page-header">
	  <h2>Isravak pogodnosti</h2>
	</div> 
	<div class="">
		<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
			<div class="panel panel-default">
				<div class="panel-body">
					 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.benefits.update', $benefit->id) }}">
						<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
							<label>Naziv</label>
							<input name="name" type="text" class="form-control" value="{{ $benefit->name }}">
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
							<label>Opis</label>
							<textarea name="description" type="text" rows="4" class="form-control" value="">{{ $benefit->description }}</textarea>
							{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
							<label>Dodatni opis</label>
							<textarea id="summernote" name="comment" >{!! $benefit->comment !!}</textarea>
							{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('url'))  ? 'has-error' : '' }}">
							<label>URL</label>
							<input name="url" type="url" class="form-control" value="{{ $benefit->url }}">
							{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group">
							<label>URL - letak</label>
							<input name="url2" type="url" class="form-control" value="{{ $benefit->url2 }}">
						
						</div>
						<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
							<label>Status</label>
							<input type="radio" class="" name="status" value="neaktivna" {!! $benefit->status == 'neaktivna' ? 'checked' : '' !!} />NEAKTIVNA 
							<input type="radio" class="" name="status" value="aktivna" {!! $benefit->status == 'aktivna' ? 'checked' : '' !!} />AKTIVNA
							{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						{{ csrf_field() }}
						{{ method_field('PUT') }}
						<input name="_token" value="{{ csrf_token() }}" type="hidden">
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/summernote_no pict.js') }}"></script>
@stop