@extends('layouts.admin')

@section('title', 'Nova prezentacija')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
	<h2>Nova prezentacija</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.presentations.store') }}">
					<div class="form-group {{ ($errors->has('theme_id'))  ? 'has-error' : '' }}">
                        <label>Tema</label>
						<select  class="form-control"  name="theme_id" value="{{ old('theme_id') }}" required >
							<option value="" disabled selected></option>
							@foreach ($educationThemes as $educationTheme)
								<option value="{{ $educationTheme->id }}">{{ $educationTheme->name }}</option>
							@endforeach
						</select>
						{!! ($errors->has('theme_id') ? $errors->first('theme_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('subject'))  ? 'has-error' : '' }}" >
						<label>Naslov</label>
						<input class="form-control"  name="subject" type="text" required />
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('article'))  ? 'has-error' : '' }}">
							<label>Tekst:</label>
							<textarea id="summernote" name="article" ></textarea>
						{!! ($errors->has('article') ? $errors->first('article', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
                        <label>Status</label>
						<input type="radio" class="" name="status" value="neaktivan" checked />NEAKTIVAN 
						<input type="radio" class="" name="status" value="aktivan" />AKTIVAN
						{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/summernote.js') }}"></script>
@stop