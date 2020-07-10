@if (! Sentinel::inRole('erp_test'))

@extends('layouts.admin')

@section('title', 'Pogodnosti')

@section('content')
<div class="container-fluid">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.benefits.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Nova pogodnost
            </a>
        </div>
        <h2>Pogodnosti za zaposlenike</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($benefits) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Opis</th>
							<th>URL</th>
							<th>Status</th>
							<th>Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
					@foreach ($benefits as $benefit) 
                        <tr>
							<td>{{ $benefit->name }}</td>
							<td>{{ $benefit->description }}</td>
							<td>{{ $benefit->url }}</td>
							<td>{{ $benefit->status }}</td>
                            <td>
                                <a href="{{ route('admin.benefits.edit', $benefit->id) }}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('admin.benefits.destroy', $benefit->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        
                                </a>
                            </td>
                        </tr>
                    @endforeach
					</tbody>
                </table>
			@else
				{{'Nema unesenih zapisa!'}}
			@endif
            </div>
        </div>
    </div>
</div>
@stop

@endif