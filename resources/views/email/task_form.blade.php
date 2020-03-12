<!doctype html>
<html lang="hr">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Zadatak</title>
		<style>
			body { 
				font-family: DejaVu Sans, sans-serif;
				font-size: 10px;
				max-width: 500px;
				padding: 20px;
				height: auto;
				overflow: hidden;
			}
			.odobri{
				width:50%;
				height:40px;
				background-color:white;
				border: 1px solid rgb(0, 102, 255);
				border-radius: 5px;
				box-shadow: 5px 5px 8px #888888;
				text-align:center;
				padding:10px;
				color:black;
				font-weight:bold;
				font-size:14px;
				margin:15px;
				float:left;
				custor:pointer
			}
			.marg_20 {
				margin-bottom:20px;
			}
			</style>
	</head>
	<body>	
		<div class="">
			<div class="">
				<h3 class="">{{ $employeeTask->task->employee->first_name . ' ' . $employeeTask->task->employee->last_name }} je postavio zadatak</h3>
				<p class="">Zadatak: <b>{{ $employeeTask->task->task }}</b></p>
				<p class="">Zaduženi djelatnik: {{ $employeeTask->employee->first_name . ' ' . $employeeTask->employee->last_name }}</p>				
				<p class="">Status: {!! $employeeTask->task->active == 1 ? 'aktivan' : 'neaktivan' !!}</p>
				
				<form name="contactform" method="post" target="_blank" action="{{ route('admin.employee_tasks.update', $employeeTask->id ) }}">
					<input type="hidden" name="mail_confirme" />
					<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="comment" required ><br>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input class="odobri" type="submit" value="Potvrdi izvršenje">
				</form>
			</div>
		</div>
	</body>
</html>