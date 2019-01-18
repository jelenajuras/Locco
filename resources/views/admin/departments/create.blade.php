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
						<input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
					</div>
					<div class="form-group" id="razina" >
						<label>Razina</label>
						<select class="form-control" name="level" id="level">
							<option value="0" >0. razina
							<option value="1" selected>1. razina
							<option value="2">2. razina
						</select>
					</div>
					<div class="form-group" id="level1">
						<label>1. razina</label>
						<select class="form-control" name="level1" value="">
							<option value="" selected>
							@foreach($departments as  $department)
								<option value="{{ $department->id }}">{{$department->name }}
							@endforeach
						</select>
					</div>

					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši radno mjesto" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
    $('#level').change(function(){
		var level = $(this).val();
		
		console.log("radi");
		console.log(level);
		
		if(level == 2){
			$('#level1').show();
		}else{
			$('#level1').hide();
		}
	});
});
</script>

@stop
