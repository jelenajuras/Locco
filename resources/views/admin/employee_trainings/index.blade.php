@extends('layouts.admin')

@section('title', 'Osposobljavanja djelatnika')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.employee_trainings.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Osposobljavanja djelatnika</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($employeeTrainings) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Djelatnik</th>
								<th>Osposobljavanje</th>
								<th>Datum</th>
								<th>Datum isteka</th>
								<th>Napomena</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($employeeTrainings as $employeeTraining)
								<tr>
									<td>{{ $employeeTraining->employee['last_name'] . ' ' . $employeeTraining->employee['first_name'] }}</td>
									<td>{{ $employeeTraining->training['name']}}</td>
									<td>{{ date('d.m.Y',strtotime($employeeTraining->date)) }}</td>
									<td>{{ date('d.m.Y',strtotime($employeeTraining->expiry_date)) }}</td>
									<td>{{ $employeeTraining->description }}</td>
									<td>
										<a href="{{ route('admin.employee_trainings.edit', $employeeTraining->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										<a href="{{ route('admin.employee_trainings.destroy', $employeeTraining->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									</td>
								</tr>
							@endforeach	
															
						</tbody>
					</table>
				@else
					{{'Nema podataka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@stop