@extends('layouts.admin')

@section('title', 'Ispravak ankete')
<link rel="stylesheet" href="{{ URL::asset('css/education.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Ispravak edukacije</h2>
</div> 
<div class="">
	<div class="col-sm-12 col-md-6 col-lg-6 col-lg-offset-1">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.educations.update', $education->id) }}">
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
                        <label>Naziv edukacije</label>
						<input name="name" type="text" class="form-control" value="{{ $education->name }}">
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
						<label>Prima:</label>
						<select class="form-control" name="to_department_id[]" id="sel1" value="{{ old('to_department_id') }}" multiple size="10">
							@foreach($departments0 as $department0)
								<option value="{{ $department0->id }}" {!!  in_array($department0->id, (explode(",",$education->to_department_id))) ? 'selected' : '' !!} >{{ $department0->name }}</option>
							@endforeach
							@foreach($departments1 as $department1)
								<option value="{{ $department1->id }}" {!!  in_array($department1->id, (explode(",",$education->to_department_id))) ? 'selected' : '' !!} >{{ $department1->name }}</option>
								@foreach($departments2 as $department2)
									@if($department2->level1 == $department1->id)
									<option value="{{ $department2->id }}" {!!  in_array($department2->id, (explode(",",$education->to_department_id))) ? 'selected' : '' !!} >-  {{ $department2->name }}</option>
									@endif
								@endforeach
							@endforeach
						</select>
					</div>
					<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
                        <label>Status</label>
						<input type="radio" class="" name="status" value="neaktivna" {!! $education->status == 'neaktivna' ? 'checked' : '' !!} />NEAKTIVNA 
						<input type="radio" class="" name="status" value="aktivna" {!! $education->status == 'aktivna' ? 'checked' : '' !!} />AKTIVNA
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
	<div class="col-sm-12 col-md-4 col-lg-2 col-lg-offset-1" style="padding:20px">
		@foreach($departments2 as $department2)
		<details>
			<summary>{{ $department2->name }}</summary>

				@foreach($employee_departments->where('department_id',$department2->id) as $employee_department)
					@if(!DB::table('employee_terminations')->where('employee_id',$employee_department->employee_id)->first() )
						<p>-  {{ $employee_department->employee['last_name'] }}</p>
					@endif
				@endforeach
			
			</details>
		@endforeach
	</div>
</div>

@stop