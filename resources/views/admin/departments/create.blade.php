@extends('layouts.admin')

@section('title', 'Novi odjel')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
  <h2>Upis novog odjela</h2>
</div> 
<div class="">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.departments.store') }}">
					<div class="form-group">
						<label>Naziv</label>
						<input name="name" type="text" class="form-control" value="{{ old('name') }}" autofocus required>
					</div>
					<div class="form-group">
						<label>E-mail</label>
						<input name="email" type="email" class="form-control" value="{{ old('email') }}" >
					</div>
					<div class="form-group" id="razina">
						<label>Razina</label>
						<select class="form-control" name="level" id="level" required>
							<option value="" selected></option>
							<option value="0" >0. razina</option>
							<option value="1" >1. razina</option>
							<option value="2" >2. razina</option>
						</select>
					</div>
					<div class="form-group" id="level_show">
						<label>1. razina</label>
						<select class="form-control"  name="level1" id="level1">
							<option value="" selected></option>
							@foreach($departments as  $department)
								@if( $department->level == 0 || $department->level == 1 )
									<option value="{{ $department->id }}"  class="level{{ $department->level }}" >{{$department->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
					<div class="form-group">
                        <label>Nadređen djelatnik</label>
						<select class="form-control" name="employee_id" id="sel1" value="{{ old('employee_id') }}"required>
							<option value="" selected disabled></option>
							@foreach($registrations as $employee)
								<option name="employee_id" value="{{ $employee->employee_id }}">
									{{ $employee->last_name . ' ' . $employee->first_name }}
								</option>
							@endforeach
						</select>
					</div>
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši odjel" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
    $('#level').change(function(){
		level = $( this ).val() ;
		console.log(level);
		if(level == 1 || level == 2){
			$('#level_show').show();
			$('#level1').prop('required',true);
			$( "#level1 option" ).each(function( index ) {
				if($( this ).hasClass('level'+ (level -1)) ) {
					$( this ).show();
					
				} else {
					$( this ).hide();
				}
			});
		}else{
			$('#level_show').hide();
		}
	});	
});
</script>
@stop
