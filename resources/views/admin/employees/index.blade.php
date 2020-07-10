@if (! Sentinel::inRole('erp_test'))
	
@extends('layouts.admin')

@section('title', 'Zapošljavanje')

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
table, td, th, tr {
    vertical-align: center;
	table-layout: fixed;
} 
</style>

@section('content')

<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.employees.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi novog kandidata
            </a>
        </div>

        <h1>Kandidati za zapošljavanje</h1>
		
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($employees) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th >Radno mjesto</th>
							<th >Ime i prezime</th>
                            <th >Datum rođenja</th>
                            <th class="not-export-column">Opcije</th>
							
                        </tr>
                    </thead>
                    <tbody id="myTable">
					<?php 
					$i = 0;
					?>
                        @foreach ($employees as $employee)
						@if(!DB::table('registrations')->where('employee_id',$employee->id)->first() )
                            <tr>
                                <td>
									<p>{{ $employee->work['odjel']}}</p>
									<p id="padding1">{{ $employee->work['naziv']}}</p>
								</td>
								<td>
									<a href="{{ route('admin.employees.show', $employee->id) }}">
										{{ $employee->first_name . ' ' . $employee->last_name }}
									</a>
								</td>
                                <td>{{ date('d.m.Y', strtotime($employee->datum_rodjenja)) }}</td>
                                <td>
                                    <a class="btn btn-block" role="button" data-toggle="collapse" href="#collapseExample{{$i}}" aria-expanded="false" aria-controls="collapseExample{{$i}}" id="stil1">
									  Opcije
									</a>
									<div class="collapse" id="collapseExample{{$i}}">
										<a href="{{action('Admin\EmployeeController@prijava_pdf', $employee->id) }}" class="btn btn-md btn-block">
											Podaci PDF
										</a>
										<a href="{{action('Admin\EmployeeController@generate_pdf', $employee->id) }}" class="btn btn-md btn-block">
											Upute prijava
										</a>
										<a href="{{action('Admin\EmployeeController@lijecnicki', $employee->id) }}" class="btn btn-md btn-block">
											Uputnica za LP
										</a>
										<!--<a href="{{action('Admin\EmployeeController@lijecnicki_pdf', $employee->id) }}" class="btn btn-default btn-md btn-block">
											Uputnica za LP PDF
										</a>-->
										<a href="{{ route('admin.employee_equipments.create', ['id' => $employee->id ]) }}" class="btn btn-md btn-block">
											Zaduži opremu
										</a>
										<a href="{{ route('admin.employee_equipments.show', ['id' => $employee->id ]) }}" class="btn btn-md btn-block  {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}">
											<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
											Zaduženje
										</a>
										<a href="{{ route('admin.employees.edit', $employee->id ) }}" class="btn btn-md btn-block">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											Ispravi
										</a>
										<a href="{{ route('admin.registrations.create', ['id' => $employee->id ]) }}" class="btn  btn-md btn-block">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											Prijavi radnika
										</a>
										<a href="{{ route('admin.employees.destroy', $employee->id) }}" class="btn btn-danger btn-md btn-block action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									</div>
                                </td>
								
                            </tr>
							<?php $i++ ?>
							@endif
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

@endif