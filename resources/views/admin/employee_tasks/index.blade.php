@extends('layouts.admin')

@section('title', 'Zadaci')
@php
  use App\Models\EmployeeTask;
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
			@if(count($employee_tasks) > 0)
               <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>Zadatak</th>
							<th>Kreirao<br>djelatnik</th>
							<th>Datum</th>
                            <th>Potvrda</th>
                        </tr>
                    </thead>
                    <tbody id="table_id">
                        @foreach ($employee_tasks as $employee_task)
                            @if ($employee_task->task->active == 1)
                                <tr>
                                    <td>{{ $employee_task->task->task }}</td>
                                    <td>{{ $employee_task->task->employee['first_name'] . ' ' . $employee_task->task->employee['last_name']}}</td>                   
                                    <td> {{ date('Y-m-d', strtotime($employee_task->created_at) ) }}</td>
                                    <td id="td1">
                                        @if ($employee_task->status == 1)
                                            <form class="form_confirmeTask" accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_tasks.update', $employee_task->id) }}">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }}
                                                <input type="hidden" value="1" >
                                                <input class="task_status1" type="submit" value="&#10004;" onclick="confirme()" title="Poništi potvrdi" >
                                            </form>
                                            <span class="">{{$employee_task->comment}} </span>
                                        @else
                                            <a class="task_status0" href="{{ route('admin.employee_tasks.edit', $employee_task->id) }}" title="Potvrdi izvršenje zadatka" >
                                                &#10004;
                                            </a>                                            
                                        @endif                                        
                                    </td>
                                </tr>
                            @endif                           
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
