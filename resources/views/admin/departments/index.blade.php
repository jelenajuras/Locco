@extends('layouts.admin')

@section('title', 'Odjeli')

@section('content')
<div class="">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.departments.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
        <h1>Odjeli</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($departments) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Nadređeni djelatnik</th>
							<th>email</th>
							<th>Krovni odjel</th>
							<th>Razina</th>
							<th>opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($departments as $department)
                            <tr>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->employee['first_name'] . ' ' . $department->employee['last_name'] }}</td>
								<td>{{ $department->email }}</td>
								<td>{{ $department->where('id', $department->level1)->value('name') }}</td>
								<td>{{ $department->level }}</td>
								<td>
                                    <a class="btn btn-primary btn-lg" href="{{ route('admin.employee_departments.edit', $department->id) }}" id="stil1">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        Snimi djelatnike na odjel
                                    </a>
                                    <a href="{{ route('admin.departments.edit', $department->id) }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
									 <a href="{{ route('admin.departments.destroy', $department->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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
