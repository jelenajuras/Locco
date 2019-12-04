@extends('layouts.admin')

@section('title', 'Nova tema')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
	<h2>Upis nove teme</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.education_themes.store') }}">
					<div class="form-group {{ ($errors->has('education_id'))  ? 'has-error' : '' }}">
                        <label>Edukacija</label>
						<select  class="form-control"  name="education_id" value="{{ old('education_id') }}" >
							<option value="" disabled selected></option>
							@foreach ($educations as $education)
								<option value="{{ $education->id }}">{{ $education->name }}</option>
							@endforeach
						</select>
						{!! ($errors->has('education_id') ? $errors->first('education_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
                        <label>Naziv teme</label>
						<input name="name" type="text" class="form-control" value="{{ old('name') }}">
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
@stop