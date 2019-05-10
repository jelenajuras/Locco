@extends('layouts.admin')

@section('title', 'Novo osposobljavanje')

@section('content')
<div class="page-header">
  <h2>Upis novog osposobljavanje</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_trainings.update', $employeeTraining->id ) }}">
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
                        <label>Djelatnik</label>
						<select class="form-control" name="employee_id" id="sel1" value="{{ old('employee_id') }}">
							<option name="employee_id" value="" selected ></option>
							@foreach ($registrations as $registration)
								@if(! $terminations->where('employee_id', $registration->employee_id)->first() )
									<option name="employee_id" value="{{ $registration->employee_id }}" {!! $employeeTraining->employee_id == $registration->employee_id ? 'selected' : '' !!}>{{ $registration->last_name  . ' ' . $registration->first_name }}</option>
								@endif
							@endforeach	
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('training_id'))  ? 'has-error' : '' }}">
                        <label>Osposobljavanje</label>
						<select class="form-control" name="training_id" id="sel1" value="{{ old('training_id') }}">
							<option name="training_id" value="" selected ></option>
							@foreach ($trainings as $training)
								<option name="training_id" value="{{ $training->id }}" {!! $employeeTraining->training_id == $training->id ? 'selected' : '' !!} >{{ $training->name }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('training_id') ? $errors->first('training_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
						<label>Datum uvjerenja:</label>
						<input name="date" type="date" class="form-control" value ="{{ $employeeTraining->date }}">
						{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('expiry_date')) ? 'has-error' : '' }}">
						<label>Datum uvjerenja:</label>
						<input name="expiry_date" type="date" class="form-control" value ="{{ $employeeTraining->expiry_date  }}">
						{!! ($errors->has('expiry_date') ? $errors->first('expiry_date', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
						<label>Napomena:</label>
						<textarea name="description" type="text" rows="3" maxlength="255" class="form-control">{{ $employeeTraining->description }}</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop