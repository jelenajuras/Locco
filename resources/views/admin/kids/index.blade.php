@extends('layouts.admin')

@section('title', 'Djeca')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')
<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.kids.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi novo dijete
            </a>
        </div>
        <h1>Djeca zaposlenika</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($kids) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Roditelj</th>
							<th>Ime i prezime</th>
							<th>Datum rođenja</th>
                            <th class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($kids as $kid)
                            <tr>
                                <td>{{ $kid->employee['first_name'] . ' ' . $kid->employee['last_name'] }}</td>
								<td>{{ $kid->ime . ' ' . $kid->prezime }}</td>
                                <td>{{ date('d.m.Y', strtotime($kid->datum_rodjenja)) }}</td>
                                  <td>
                                    <a href="{{ route('admin.kids.edit', $kid->id) }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    <a href="{{ route('admin.kids.destroy', $kid->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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
			{!! $kids->render() !!}
        </div>
    </div>
</div>
@stop
