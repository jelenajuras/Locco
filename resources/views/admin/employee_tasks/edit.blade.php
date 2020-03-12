@extends('layouts.admin')

@section('title', 'Potvrda zadatka')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="forma col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
	<h2>Potvrda zadatka</h2>
	<div class="panel-body">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_tasks.update', $employee_task->id) }}">
			<input type="hidden" name="mail_confirme" />
			<div class="form-group">
				<label>Komentar</label>
				<input class="form-control" type="text" name="comment" required ><br>
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Potvrdi izvršenje" id="stil1">
		</form>
	</div>
</div>
@stop

