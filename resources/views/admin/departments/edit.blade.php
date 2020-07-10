@extends('layouts.admin')

@section('title', 'Ispravak odjela')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')

<div class="page-header">
  <h2>Ispravak odjela</h2>
</div> 
<div class="">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.departments.update', $department->id ) }}" >
					<div class="form-group">
						<label>Naziv</label>
						<input name="name" type="text" class="form-control" value="{{ $department->name }}" required>
					</div>
					<div class="form-group">
						<label>E-mail</label>
						<input name="email" type="email" class="form-control" value="{{  $department->email  }}" >
					</div>
					<div class="form-group">
						<label>Razina</label>
						<select class="form-control" name="level" id="level">
							<option value="0" {!! $department->level == '0' ? 'selected' : '' !!} >0. razina
							<option value="1" {!! $department->level == '1' ? 'selected' : '' !!} >1. razina
							<option value="2"  {!! $department->level == '2' ? 'selected' : '' !!}>2. razina
						</select>
					</div>
					<div class="form-group" id="level_show">
						<label>1. razina</label>
						<select class="form-control" name="level1" id="level1" >
							<option value="" selected></option>
							@foreach($departments as  $odjel)
								@if( $odjel->level == 0 || $odjel->level == 1 )
									<option value="{{ $odjel->id }}"  class="level{{ $odjel->level }}"  {!! $odjel->id == $department->level1 ? 'selected' : '' !!} >{{$odjel->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
					<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
                        <label>Nadređen djelatnik</label>
						<select class="form-control" name="employee_id" id="sel1" value="{{ old('employee_id') }}" required >
							<option value="" selected disabled></option>
							@foreach($registrations as $employee)
								<option name="employee_id" value="{{ $employee->employee_id }}" {!! $department->employee_id ==  $employee->employee_id ? 'selected' : '' !!}  >
									{{  $employee->last_name . ' ' . $employee->first_name }}
								</option>
							@endforeach
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi podatke" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		level = $( '#level' ).val() ;
		if(level == 1 || level == 2){
			$('#level_show').show();
		} else {
			$('#level_show').hide();
		}
		$('#level').change(function(){
			level = $( this ).val() ;
			
			if(level == 1 || level == 2){
				$('#level_show').show();
				$('#level1').attr('required','true');
				console.log(level);
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