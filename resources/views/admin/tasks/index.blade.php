@extends('layouts.admin')

@section('title', 'Zadaci')
@php
  use App\Models\EmployeeTask;
  use App\Models\Employee;
@endphp
@section('content')
<div class="Jmain">
    <div class="page-header">
		<div class="btn-toolbar pull-right">
            <a class="btn btn-primary btn-lg" href="{{ route('admin.tasks.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Dodaj zadatak
            </a>
        </div>
        <h1>Zadaci</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($tasks) > 0)
               <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>Zadatak</th>
							<th>Zaduženi<br>djelatnik</th>
							<th>Kreirao<br>djelatnik</th>
							<th>Početni<br>datum</th>
							<th>Završni<br>datum</th>
							<th>Interval | Period</th>
							<th>Aktivan</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody id="table_id">
                        @foreach ($tasks as $task)
                        @php
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
                            $date = new DateTime($task->start_date);

                           
                            $employeeTask = EmployeeTask::where('task_id',$task->id)->orderBy('created_at','DESC')->get();
                            $employee_ids = explode(',', $task->to_employee_id);
                        @endphp                       
                            <tr>
                                <td>
                                    @if (count($employeeTask)>0)
                                        <a href="{{ route('admin.tasks.show', $task->id) }}">{{ $task->task }}</a>
                                    @else
                                        {{ $task->task }}
                                    @endif
                                </td>
								<td>
                                    @foreach ($employee_ids as $employee_id)
                                        @php
                                            $employee = Employee::where('id',$employee_id )->first();
                                        @endphp
                                        {{ $employee['first_name'] . ' ' . $employee['last_name']}}<br>
                                    @endforeach
                                    
                                </td>
                                <td>{{ $task->employee['first_name'] . ' ' . $task->employee['last_name']}}</td>                   
                                <td>{{ date_format($date, 'Y-m-d' ) }}</td>
                                <td>{!! $task->end_date ?  date('d.m.Y', strtotime($task->end_date)) : '' !!}</td>
								<td>{{ $interval }} {!! $period ?  ' | ' . $period : '' !!} </td>
								<td>{!! $task->active == 1 ?  'aktivan' : 'neaktivan' !!}</td>
								<td id="td1">
									<a href="{{ route('admin.tasks.edit', $task->id) }}">
										<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</a>
									<a href="{{ route('admin.tasks.destroy', $task->id) }}" class="action_confirm {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}" data-method="delete" data-token="{{ csrf_token() }}">
										<i class="far fa-trash-alt"></i>
									</a>
								</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
				</body>
				@else
					{{'Nema unesenih zadataka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@stop
