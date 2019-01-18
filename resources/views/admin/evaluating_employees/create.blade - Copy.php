@extends('layouts.admin')

@section('title', 'Dodjela zaposlenika')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Dodjela zaposlenika za ocjenjivanje</h2>
</div> 
<div class="row">
	<div class="col-12">
		<div class="panel panel-default">
			<div class="panel-body">
				 
					@foreach($employees as $employee)
						@if(!DB::table('employee_terminations')->where('employee_id',$employee->employee_id)->first() )
							<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.evaluating_employees.store') }}">
								<div class="box form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
									<label class="empl" >{{ $employee->employee['first_name'] . ' ' . $employee->employee['last_name'] }}</label>	
									<input type="hidden" name="employee_id" value="{{ $employee->id}}" />
								
									@foreach($employees as $employee)
										@if(!DB::table('employee_terminations')->where('employee_id',$employee->employee_id)->first() )
											<span class="ev_empl" ><input type="checkbox" name="ev_employee_id" value="{{ $employee->employee_id }}">{{ $employee->employee['first_name'] . ' ' . $employee->employee['last_name'] }}</span>
										@endif
									@endforeach
									<input name="_token" value="{{ csrf_token() }}" type="hidden">
									<input class="btn_align" type="submit" value="&#10004" title="Snimi promjenu" >
								</div>
							</form>
						@endif
					@endforeach

				
					
				
			</div>
		</div>
	</div>
</div>

@stop