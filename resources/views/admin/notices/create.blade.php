@extends('layouts.admin')

@section('title', 'Nova obavijest')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}" type="text/css" >
@section('content')
<div class="page-header">
  <h2>Upis nove obavijesti</h2>
</div> 
<div class="">
	<div class="col-sm-12 col-md-6 col-lg-6 col-lg-offset-1">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.notices.store') }}">
					<input name="employee_id" type="hidden" class="form-control" value="{{ $user }}" />
					<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}" >
						<label>Prima:</label>
						<select class="form-control select_department" name="to_department_id[]" id="sel1" value="{{ old('to_department_id') }}" multiple size="10" required>
							@foreach($departments->where('level',0) as $department0)
								<option class="{{ $department0->name }}" value="{{ $department0->id }}">{{ $department0->name }}</option>
							@endforeach
							@foreach($departments->where('level',1) as $department1)
								<option value="{{ $department1->id }}">{{ $department1->name }}</option>
								@foreach($departments->where('level',2) as $department2)
									@if($department2->level1 == $department1->id)
									<option value="{{ $department2->id }}">-  {{ $department2->name }}</option>
									@endif
								@endforeach
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label>Tip obavijesti:</label>
						<select class="form-control notice_type" name="type" id="sel1" value="{{ old('type') }}" required>
							<option value="" disabled selected></option>
							<option value="uprava">Obavijest uprave</option>
							<option value="najava">Najava aktivnosti</option>
						</select>
					</div>
					<div class="form-group {{ ($errors->has('subject')) ? 'has-error' : '' }}">
						<label>Subjekt:</label>
						<input name="subject" type="text" class="form-control" required>
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('notice')) ? 'has-error' : '' }}">
						<label>Obavijest:</label>
						<textarea id="summernote" name="notice" required></textarea>
						
						<!--<textarea rows="10" name="notice" type="text" class="form-control"></textarea>-->
						{!! ($errors->has('notice') ? $errors->first('notice', '<p class="text-danger">:message</p>') : '') !!}
					</div>

					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Pošalji obavijest" id="stil1">
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-4 col-lg-2 col-lg-offset-1" style="padding:20px">
		@foreach($departments->where('level',0) as $department0)
			@if(count($employee_departments->where('department_id',$department0->id) )>0)
				<details class="level0">
					<summary>{{ $department0->name }}</summary>
					@foreach($employee_departments->where('department_id',$department0->id) as $employee_department)
						@if(!DB::table('employee_terminations')->where('employee_id',$employee_department->employee_id)->first() )
							<p>{{ $employee_department->employee['first_name'] . ' ' .  $employee_department->employee['last_name']  }}</p>
						@endif
					@endforeach
				
				</details>
			@else 
				<p  class="level0">{{ $department0->name }}</p>
			@endif
			@foreach($departments->where('level',1)->where('level1', $department0->id ) as $department1)
				@if(count($employee_departments->where('department_id',$department1->id) )>0)
					<details  class="level1" style="padding-left:20px">
						<summary>{{ $department1->name }}</summary>
						@foreach($employee_departments->where('department_id',$department1->id) as $employee_department)
							@if(!DB::table('employee_terminations')->where('employee_id',$employee_department->employee_id)->first() )
								<p>{{ $employee_department->employee['first_name'] . ' ' .  $employee_department->employee['last_name']  }}</p>
							@endif
						@endforeach
					
					</details>
				@else 
					<p  class="level1" style="padding-left:20px">{{ $department1->name }}</p>
				@endif	
	

				@foreach($departments->where('level',2)->where('level1', $department1->id ) as $department2)
					<details  class="level2" style="padding-left:40px">
						<summary>{{ $department2->name }}</summary>
						@foreach($employee_departments->where('department_id',$department2->id) as $employee_department)
							@if(!DB::table('employee_terminations')->where('employee_id',$employee_department->employee_id)->first() )
								<p>{{ $employee_department->employee['first_name'] . ' ' .  $employee_department->employee['last_name'] }}</p>
							@endif
						@endforeach
					</details>
				@endforeach
			@endforeach
		@endforeach
		

	</div>
</div>
<script src="{{ asset('js/summernote.js') }}"></script>
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