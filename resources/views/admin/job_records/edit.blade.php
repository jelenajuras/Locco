@extends('layouts.admin')

@section('title', 'Novi zadatak')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Upis novog zadatka</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" enctype="multipart/form-data" action="{{ route('admin.job_records.update', $job_record->id) }}">
					<div class="form-group {{ ($errors->has('task'))  ? 'has-error' : '' }}">
                        <label>Zadatak</label>
						<input name="task" type="text" class="form-control" value="{{ $job_record->task }}" required >
						{!! ($errors->has('task') ? $errors->first('task', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('task_manager'))  ? 'has-error' : '' }}">
                        <label>Voditelj zadatka</label>
						<select class="form-control" name="task_manager" id="sel1" value="{{ old('task_manager') }}">
							<option value="" selected disabled ></option>
							@foreach ($registrations as $registration)
								@if(! $terminations->where('employee_id', $registration->employee_id)->first() )
									<option value="{{ $registration->employee_id }}" {!! $job_record->task_manager == $registration->employee_id ? 'selected' : '' !!}>{{ $registration->employee['last_name'] . ' ' . $registration->employee['first_name'] }}</option>
								@endif
							@endforeach	
						</select>
						{!! ($errors->has('task_manager') ? $errors->first('task_manager', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
						<label>Datum:</label>
						<input name="date" type="date" class="form-control" value ="{{ $job_record->date }}">
						{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('time')) ? 'has-error' : '' }}">
						<label>Vrijeme:</label>
						<input name="time" type="time" class="form-control" value ="{{  $job_record->time }}">
						{!! ($errors->has('time') ? $errors->first('time', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
                        <label>Djelatnik</label>
						<select class="form-control" name="employee_id" id="sel1" value="{{ old('employee_id') }}">
							<option value="" selected disabled ></option>
							@foreach ($registrations as $registration)
								@if(! $terminations->where('employee_id', $registration->employee_id)->first() )
									<option value="{{ $registration->employee_id }}" {!! $job_record->employee_id == $registration->employee_id ? 'selected' : '' !!}>{{ $registration->employee['last_name'] . ' ' . $registration->employee['first_name'] }}</option>
								@endif
							@endforeach	
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('odjel')) ? 'has-error' : '' }}">
						<label>Odjel:</label>
						<input name="odjel" type="text" class="form-control" value="{{  $job_record->odjel }}" >
						{!! ($errors->has('odjel') ? $errors->first('odjel', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop