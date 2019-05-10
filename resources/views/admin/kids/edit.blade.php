@extends('layouts.admin')

@section('title', 'Ispravi dijete')

@section('content')
<div class="page-header">
  <h2>Ispravak podataka djeteta</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.kids.update', $kid->id) }}">
				 
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
                        <label>Roditelj</label>
						<select class="form-control" name="employee_id" id="sel1" value="{{  $kid->employee_id  }}">
							@foreach (DB::table('employees')->orderBy('last_name','ASC')->get() as $employee)
								<option name="employee_id" value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
							@endforeach	
							<option selected="selected" value="{{ $kid->employee_id }}">{{ $kid->employee['first_name'] . ' ' . $kid->employee['last_name']  }}</option>
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('ime')) ? 'has-error' : '' }}">
						<label>Ime:</label>
						<input name="ime" type="text" class="form-control" value="{{ $kid->ime }}">
						{!! ($errors->has('ime') ? $errors->first('ime', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('prezime')) ? 'has-error' : '' }}">
						<label>Prezime:</label>
						<input name="prezime" type="text" class="form-control" value="{{ $kid->prezime }}">
						{!! ($errors->has('prezime') ? $errors->first('prezime', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Datum rođenja</label>
						<input name="datum_rodjenja" class="date form-control" type="date" value="{{ date('Y-m-d', strtotime($kid->datum_rodjenja)) }}">
						{!! ($errors->has('datum_rodjenja') ? $errors->first('datum_rodjenja', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi podatke" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop