<!doctype html>
<html lang="hr">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Zadatak</title>
		<style>
			
		</style>
	</head>
	<body>
		<?php 
		use App\Models\Employee;
			switch ( $task->interval ) {
				case 'no_repeat':
					$interval = 'Bez ponavljanja';
					$period = '';
					break;
				case 'every_day':
					$interval = 'Dnevno';
					$period = '';
					break;
				case 'once_week':
					$interval = 'Tjedno';
					$period = trans('basic.'.date("l", strtotime($task->start_date)));
					break;
				case 'once_month':
					$interval = 'Mjesečno';
					$period =  trans('basic.'.date("F", strtotime($task->start_date)));
					break;
				case 'once_year':
					$interval = 'Godišnje';
					$period = '';
					break;
				default:
					/*  $array_interval = explode('-', $task->interval); */
					$interval = $task->interval;
					$period = '';
			}
		?>
		<div class="">
			<div class="">
				<h3 class="">{{ $task->employee->first_name . ' ' . $task->employee->last_name }} je postavio zadatak</h3>
				<p class="">Zadatak: <b>{{ $task->task }}</b></p>
				<p class="">
					@php
						$employee_ids = explode(",", $task->to_employee_id);
					@endphp
					Zaduženi djelatnici: 
					@foreach ($employee_ids as $id)
						@php
							$employee = Employee::where('id', $id)->first();
						@endphp
						{{ $employee->first_name . ' ' . $employee->last_name . '; '}}						
					@endforeach					
				</p>
				<p class="">Početni datum: {{ $task->start_date }}</p>
				<p class="">Interval: {{ $interval }} {!! $period ?  ' | ' . $period : '' !!} </p>
				<p class="">Status: {!! $task->active == 1 ? 'aktivan' : 'neaktivan' !!}</p>
			{{-- @if ($task->active == 1)
					<a href="{{ $link }}" style="padding: 5px 10px; border-radius: 3px; color: #fff;
					background-color: #007bff; border-color: #007bff" class="btn btn-primary">Link na zadatke</a>
				@endif	 --}}			
			</div>
		</div>
	</body>
</html>