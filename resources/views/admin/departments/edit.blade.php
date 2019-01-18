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
						<input name="email" type="email" class="form-control" value="{{  $department->email  }}" required>
					</div>
					<div class="form-group">
						<label>Razina</label>
						<select class="form-control" name="level" id="level">
							<option value="1" {!! $department->level == '1' ? 'selected' : '' !!} >1. razina
							<option value="2"  {!! $department->level == '2' ? 'selected' : '' !!}>2. razina
						</select>
					</div>
					<div class="form-group" id="level1">
						<label>1. razina</label>
						<select class="form-control" name="level1" value="">
							<option value="">
							@foreach($departments as  $odjel)
								<option value="{{ $odjel->id }}" {!! $odjel->id == $department->level1 ? 'selected' : '' !!} >{{$odjel->name }}
							@endforeach
						</select>
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
   var level = $('#level').val();
   if(level == 2){
		$('#level1').show();
	}else{
		$('#level1').hide();
	}
   $('#level').change(function(){
		var level = $('#level').val();
		
		if(level == 2){
			$('#level1').show();
		}else{
			$('#level1').hide();

		}
	});
});
</script>
@stop