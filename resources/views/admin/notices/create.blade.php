@extends('layouts.admin')

@section('title', 'Nova obavijest')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}" type="text/css" >
@section('content')
<div class="page-header">
  <h2>Upis nove obavijesti</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.notices.store') }}">
					<input name="employee_id" type="hidden" class="form-control" value="{{ $user }}" />
					<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
						<label>Prima:</label>
						<select class="form-control" name="to_department_id[]" id="sel1" value="{{ old('to_department_id') }}" multiple size="10">
							@foreach($departments0 as $department0)
								<option value="{{ $department0->id }}">{{ $department0->name }}</option>
							@endforeach
							@foreach($departments1 as $department1)
								<option value="{{ $department1->id }}">{{ $department1->name }}</option>
								@foreach($departments2 as $department2)
									@if($department2->level1 == $department1->id)
									<option value="{{ $department2->id }}">-  {{ $department2->name }}</option>
									@endif
								@endforeach
							@endforeach
						</select>
					</div>
					<div class="form-group {{ ($errors->has('subject')) ? 'has-error' : '' }}">
						<label>Subjekt:</label>
						<input name="subject" type="text" class="form-control">
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('notice')) ? 'has-error' : '' }}">
						<label>Obavijest:</label>
						<textarea id="summernote" name="notice"></textarea>
						
						<!--<textarea rows="10" name="notice" type="text" class="form-control"></textarea>-->
						{!! ($errors->has('notice') ? $errors->first('notice', '<p class="text-danger">:message</p>') : '') !!}
					</div>

					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Pošalji obavijest" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
  $('#summernote').summernote({
	  height: 200
  });
});
</script>
<script>
$('option').mousedown(function(e) {
    e.preventDefault();
    $(this).prop('selected', !$(this).prop('selected'));
    return false;
});
</script>
@stop