@extends('layouts.admin')

@section('title', 'Novo dijete')

@section('content')
<div class="page-header">
  <h2>Upis novog djeteta</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.kids.store') }}">
				 
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
                        <label>Roditelj</label>
						<select class="form-control" name="employee_id" id="sel1" value="{{ old('employee_id') }}">
							@foreach (DB::table('employees')->orderBy('last_name','ASC')->get() as $employee)
								<option name="employee_id" value="{{ $employee->id }}">{{ $employee->last_name  . ' ' . $employee->first_name }}</option>
							@endforeach	
							<option selected="selected" value=""></option>
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('ime')) ? 'has-error' : '' }}">
						<label>Ime:</label>
						<input name="ime" type="text" class="form-control">
						{!! ($errors->has('ime') ? $errors->first('ime', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('prezime')) ? 'has-error' : '' }}">
						<label>Prezime:</label>
						<input name="prezime" type="text" class="form-control">
						{!! ($errors->has('prezime') ? $errors->first('prezime', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group">
						<label>Datum rođenja</label>
						<input name="datum_rodjenja" class="date form-control" type="date" value = "">
						{!! ($errors->has('datum_rodjenja') ? $errors->first('datum_rodjenja', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši dijete" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop