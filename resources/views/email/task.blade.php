<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
	</head>
	<style>
	body { 
		font-family: DejaVu Sans, sans-serif;
		font-size: 10px;
	}
	</style>
	<?php 
		 switch ( $employeeTask->task->interval ) {
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
	<body>
		<h3>Zadatak: {{ $employeeTask->task->task }}</h3>
		<p>Zadatak postavio: {{ $employeeTask->task->employee->first_name . ' ' . $employeeTask->task->employee->last_name }}</p>
		<p>Djelatniku: {{ $employeeTask->employee->first_name . ' ' . $employeeTask->employee->last_name }}</p>
		<p class="">Početni datum: {{  $employeeTask->task->start_date }}</p>
		<p class="">Interval: {{ $interval }} {!! $period ?  ' | ' . $period : '' !!} </p>
	</body>
</html>