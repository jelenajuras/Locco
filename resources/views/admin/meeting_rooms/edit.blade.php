@extends('layouts.admin')

@section('title', 'Nova soba')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
  <h2>Upis nove sobe</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.meeting_rooms.update', $meeting_room->id)}}">
					<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
						<label>Naziv sobe:</label>
						<input name="name" type="text" class="form-control" value="{{ $meeting_room->name }}">
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('location')) ? 'has-error' : '' }}">
						<label>Lokacija:</label>
						<input name="location" type="text" class="form-control" value="{{  $meeting_room->location }}" >
						{!! ($errors->has('location') ? $errors->first('location', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
						<label>Opis:</label>
						<input name="description" type="text" class="form-control" value="{{  $meeting_room->description }}" >
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
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

@stop