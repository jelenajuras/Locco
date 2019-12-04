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
						<select class="form-control" name="to_department_id[]" id="sel1" value="{{ old('to_department_id') }}" multiple size="10" required>
							@foreach($departments->where('level',0) as $department0)
								<option value="{{ $department0->id }}" {!!  $notice->to_department_id ==  $department0->id ? 'selected' : '' !!}>{{ $department0->name }}</option>
							@endforeach
							@foreach($departments->where('level',1) as $department1)
								<option value="{{ $department1->id }}" {!!  $notice->to_department_id ==  $department1->id ? 'selected' : '' !!} >{{ $department1->name }}</option>
								@foreach($departments->where('level',2) as $department2)
									@if($department2->level1 == $department1->id)
									<option value="{{ $department2->id }}" {!!  $notice->to_department_id ==  $department2->id ? 'selected' : '' !!}>-  {{ $department2->name }}</option>
									@endif
								@endforeach
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label>Tip obavijesti:</label>
						<select class="form-control" name="type" id="sel1" value="{{ old('type') }}" required>
							<option value="" disabled selected></option>
							<option value="uprava" {!!  $notice->type == 'uprava' ? 'selected' : '' !!}>Obavijest uprave</option>
							<option value="najava"  {!!  $notice->type == 'najava' ? 'selected' : '' !!} >Najava aktivnosti</option>
						</select>
					</div>
					<div class="form-group {{ ($errors->has('subject')) ? 'has-error' : '' }}">
						<label>Subjekt:</label>
						<input name="subject" type="text" class="form-control" value="{{ $notice->subject }}" required>
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('notice')) ? 'has-error' : '' }}">
						<label>Obavijest:</label>
						<textarea id="summernote" name="notice" required>{{ $notice->notice }}</textarea>
						{!! ($errors->has('notice') ? $errors->first('notice', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ method_field('PUT') }}
					{{ csrf_field() }}
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
<script>
	$('.notice_type').change(function(){
		if($(this).val() == 'najava') {
			$('.select_department').removeAttr('multiple');
			$( '.select_department' ).find('option').removeAttr('selected');
			$( '.select_department' ).find('option.Svi').attr('selected','selected');
			$( '.select_department' ).val('10');
			console.log($( '.select_department' ).val());
		} else {
			$( '.select_department' ).attr('multiple', true);
		}
		
		console.log('klik');
	});

</script>
@stop