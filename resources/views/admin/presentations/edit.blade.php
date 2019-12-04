@extends('layouts.admin')

@section('title', 'Novi članak')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
	<h2>Novi članak</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.presentations.update', $presentation->id) }}">
					<div class="form-group {{ ($errors->has('theme_id'))  ? 'has-error' : '' }}">
                        <label>Tema</label>
						<select  class="form-control"  name="theme_id" required >
							<option value="" disabled selected></option>
							@foreach ($educationThemes as $educationTheme)
								<option value="{{ $educationTheme->id }}"  {!! $educationTheme->id == $presentation->theme_id ? 'selected' : '' !!} >{{ $educationTheme->name }}</option>
							@endforeach
						</select>
						{!! ($errors->has('theme_id') ? $errors->first('theme_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('subject'))  ? 'has-error' : '' }}">
						<label>Naslov</label>
						<input class="form-control" name="subject" type="text" value="{{$presentation->subject }}" required />
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('article'))  ? 'has-error' : '' }}">
                        <label>Tekst</label>
						<textarea id="summernote" name="article">{{ $presentation->article  }}</textarea>
						{!! ($errors->has('article') ? $errors->first('article', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
                        <label>Status</label>
						<input type="radio" class="" name="status" value="neaktivan" {!! $presentation->status == 'neaktivan' ? 'checked' : '' !!} />NEAKTIVAN
						<input type="radio" class="" name="status" value="aktivan" {!! $presentation->status == 'aktivan' ? 'checked' : '' !!} />AKTIVAN
						{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ method_field('PUT') }}
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/summernote.js') }}"></script>
@stop