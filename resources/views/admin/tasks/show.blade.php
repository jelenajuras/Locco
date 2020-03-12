@extends('layouts.admin')

@section('title', 'Zadaci')

@section('content')
<div class="Jmain">
    <div class="page-header">
		<div class="btn-toolbar pull-right">
            <a class="btn btn-primary btn-lg" href="{{ route('admin.tasks.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Dodaj zadatak
            </a>
        </div>
        <h2>Zadatak - {{ $task->task }}</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($employee_tasks) > 0)
               <table id="table_id" class="display">
                    <thead>
                        <tr>
							<th>Zaduženi<br>djelatnik</th>
							<th>Datum</th>
							<th>Status</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody id="table_id">
                        @foreach ($employee_tasks as $employee_task)
                            <tr>
								<td>{{ $employee_task->employee['first_name'] . ' ' . $employee_task->employee['last_name']}}</td>
                                <td>{{ date('d.m.Y', strtotime($employee_task->created_at)) }}</td>
								<td>{!! $employee_task->status == 1 ?  '<i class="fas fa-check" style="color:green"></i>' : '<i class="fas fa-minus" style="color:red"></i>' !!}</td>
								<td id="td1">
                                    <form class="form_confirmeTask" accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_tasks.update', $employee_task->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <input type="hidden" value="1" >
			                            <input type="submit" value="&#10004;" onclick="confirme()" title="Potvrdi izvršenje zadatka" >
                                    </form>
                                 
									<a href="{{ route('admin.employee_tasks.destroy', $employee_task->id) }}" class="action_confirm {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}" data-method="delete" data-token="{{ csrf_token() }}">
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
<script>
    function confirme() {
      var txt;
      var r = confirm("Press a button!");
      if (r == true) {
        txt = "You pressed OK!";
      } else {
        txt = "You pressed Cancel!";
      }
      console.log( txt);
    }
    </script>
@stop