@extends('layouts.admin')

@section('title', 'Odjavljeni radnici')

<style>
#padding1 {
    padding-left: 30px;
}
th {
    font-size: 12px;
	text-align: center;
} 
td {
    font-size: 14px;
} 
table, td, th, tr {
    vertical-align: center;
	table-layout: fixed;
} 
</style>

@section('content')

<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.employee_terminations.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Odjavi radnika
            </a>
        </div>
        <h1>Popis odjavljenih radnika</h1>
		
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
			@if(count($employee_terminations) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th width="100">Djelatnik</th>
							<th width="100">Vrsta otkaza</th>
                            <th width="50">Otkazni rok</th>
							<th width="70">Datum odjave</th>
							<th width="100">Napomena</th>
                            <th width="80" class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($employee_terminations as $employee_termination)
                            <tr>
                                
								<td>
									<a href="{{ route('admin.employee_terminations.show', $employee_termination->id) }}">
										{{ $employee_termination->employee['first_name'] . ' ' . $employee_termination->employee['last_name'] }}
									</a>
								</td>
								<td>{{ $employee_termination->termination['naziv'] }}</td>
								<td>{{ $employee_termination->otkazni_rok }}</td>
								<td>{{ date('d.m.Y.', strtotime($employee_termination->datum_odjave)) }}
								<td>{{ $employee_termination->napomena }}</td>
								<td>
                                    <a href="{{ route('admin.employee_terminations.edit', $employee_termination->id) }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
									<a href="{{ route('admin.employee_terminations.destroy', $employee_termination->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}" >
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
