@extends('layouts.admin')

@section('title', 'Privremeni djelatnici')

<style>
#padding1 {
    padding-left: 30px;
}
th {
    font-size: 12px;
} 
td {
    font-size: 14px;
} 
input {
	border: 1px solid;
	border-color: d9d9d9;
	border-radius: 3px;
	padding: 3px;
}
</style>

@section('content')
<div class="">
	<div class="page-header">
		<div class="btn-toolbar pull-right">
            <a class="btn btn-primary btn-lg" href="{{ route('admin.temporary_employees.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Dodaj djelatnika
            </a>
        </div>
        <h1>Privremeni djelatnici</h1>
    </div>       
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive" id="tblData">
			@if(count($temporaryeEmployees) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
							<th width="100" onclick="sortTable(0)">Ime i prezime</th>
							<th width="80" onclick="sortTable(1)">Datum prijave</th>
							<th width="80" onclick="sortTable(2)">Odjava</th>
							<th width="150" onclick="sortTable(3)">Radno mjesto</th>
							<th width="150" onclick="sortTable(4)">Nadređeni djelatnik</th>
						    <th width="150" class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
					<?php 
					$i = 0;
					?>
						@foreach ($temporaryeEmployees as $temporaryeEmployee)
                            <tr>
								<td><a href="{{ route('admin.temporary_employees.show', $temporaryeEmployee->id) }}">{{ $temporaryeEmployee->last_name  . ' '. $temporaryeEmployee->first_name }}</a></td>
                                <td>{{ date('d.m.Y.', strtotime($temporaryeEmployee->datum_prijave)) }}</td>
								<td>{!!  $temporaryeEmployee->odjava == 1 ? 'odjavljen' : '' !!}</td>
								<td>{{ $temporaryeEmployee->work['odjel'] . ' - ' . $temporaryeEmployee->work['naziv'] }}</td>
								<td>{{ $temporaryeEmployee->employee['last_name'] . ' ' . $temporaryeEmployee->employee['first_name'] }}</td>							
								<td>
									@if(Sentinel::inRole('administrator'))
										<a href="{{ route('admin.temporary_employees.edit', $temporaryeEmployee->id) }}" >
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
										</a>
										<a href="{{ route('admin.temporary_employees.destroy', $temporaryeEmployee->id) }}" class="action_confirm {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
										<a href="{{ route('admin.temporary_employee_requests.index', ['id' => $temporaryeEmployee->id ]) }}" title="Zahtjevi djelatnika" >
											<i class="fas fa-list"></i>
										</a>
									@endif
								</td>	
							</tr>
							<?php $i++ ?>
							
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
