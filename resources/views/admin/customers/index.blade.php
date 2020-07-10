@if (! Sentinel::inRole('erp_test'))
	

@extends('layouts.admin')

@section('title', 'Naručitelji')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')
<div class="Jmain">
    <div class="page-header">
		<div class="btn-toolbar pull-right">
            <a class="btn btn-primary btn-lg" href="{{ route('admin.customers.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Dodaj klijenta
            </a>
        </div>
        <h1>Klijenti</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($customers) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Firma</th>
                            <th>Adresa</th>
							<th>Grad</th>
							<th>OIB</th>
                            <th class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
					@foreach ($customers as $customer)
                        <tr>
							<td>{{ $customer->naziv }}</td>
							<td>{{ $customer->adresa}}</td>
							<td>{{ $customer->grad}}</td>
							<td>{{ $customer->oib}}</td>
                            <td id="td1">
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" >
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                               <a href="{{ route('admin.customers.destroy', $customer->id) }}" class="action_confirm {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}" data-method="delete" data-token="{{ csrf_token() }}">
                                   <i class="far fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
				@else
					<td>{{'Nema unesenih naručitelja!'}}</td>
				@endif
            </div>

        </div>
    </div>
</div>
@stop

@endif