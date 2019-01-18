@extends('layouts.admin')

@section('title', 'Nova obavijest')

@section('content')
<div class="page-header">
  <h2>Ispravak obavijesti</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.notices.update', $notice->id) }}">
					<input name="employee_id" type="hidden" class="form-control" value="{{ $user }}" >
					<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
						<select class="form-control" name="to_department_id" id="sel1" value="{{ old('to_department_id') }}">
							<option selected="selected" value="">Prima...</option>
								@foreach($departments as $department)
									<option name="svi" value="{{ $department->id }}" {!!  $notice->to_department_id ==  $department->id ? 'selected' : '' !!}>{!! $department->level == '2' ? '    - ' : '' !!}{{ $department->name }}
									</option>
								@endforeach
						</select>
					</div>
					<div class="form-group {{ ($errors->has('subject')) ? 'has-error' : '' }}">
						<label>Subjekt:</label>
						<input name="subject" type="text" class="form-control" value="{{ $notice->subject }}">
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('notice')) ? 'has-error' : '' }}">
						<label>Obavijest:</label>
						<textarea id="summernote" name="notice">{{ $notice->notice }}</textarea>
						{!! ($errors->has('notice') ? $errors->first('notice', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi obavijest" id="stil1">
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
@stop