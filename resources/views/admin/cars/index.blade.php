@if (! Sentinel::inRole('erp_test'))
    
@extends('layouts.admin')

@section('title', 'Vozila')

@section('content')
<div class="">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.cars.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novo vozilo
            </a>
        </div>
        <h2>Vozila</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($vozila) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Registracija</th>
                            <th>Proizvođač, model</th>
							<th>Šasija</th>
							<th>Prva registracija</th>
							<th>Zadnja registracija</th>
							<th>Trenutni kilometri</th>
							<th>Zadnji servis</th>
							<th>Djelatnik</th>
                            
							<th>Opcije</th>
                        </tr>
                    </thead>
                     <tbody id="myTable">
					@foreach ($vozila as $vozilo) 
                        <tr>
							<td>{{ $vozilo->registracija }}</td>
							<td>{{ $vozilo->proizvođač .' - '. $vozilo->model }} </td>
							<td>{{ $vozilo->šasija }} </td>
							<td>{{ $vozilo->prva_registracija }} </td>
							<td>{{ $vozilo->zadnja_registracija }} </td>
							<td>{{ $vozilo->trenutni_kilometri }} </td>
							<td>{{ $vozilo->zadnji_servis }} </td>
							<td>{{ $vozilo->employee['first_name'] . " " . $vozilo->employee['last_name'] }} </td>
							
                            <td>
                                <a href="{{ route('admin.cars.edit', $vozilo->id) }}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                                <!--<a href="{{ route('admin.cars.destroy', $vozilo->id) }}" class="btn btn-danger action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        Delete
                                </a>-->
                            </td>
                        </tr>
                    @endforeach
					</tbody>
                </table>
			@else
				{{'Nema unesenih vozila!'}}
			@endif
            </div>
        </div>
    </div>
</div>
@stop

@endif