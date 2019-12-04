@extends('layouts.admin')

@section('title', 'Isravak kategorije')

@section('content')
<div class="container-fluid">
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
					 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.ad_categories.update', $adCategory->id) }}">
						<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
							<label>Naziv</label>
							<input name="name" type="text" class="form-control" value="{{ $adCategory->name }}">
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/summernote_no pict.js') }}"></script>
@stop