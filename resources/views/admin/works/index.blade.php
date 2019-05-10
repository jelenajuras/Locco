@extends('layouts.admin')

@section('title', 'Radna mjesta')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')

<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.works.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi novo radno mjesto
            </a>
        </div>
		
        <h1>Radna mjesta</h1>
		
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($works) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Odjel</th>
                            <th>Naziv</th>
                            <th>Opis rada</th>
							<th>Pravilnik</th>
							<th>Točke</th>
							<th>Nadređeni djelatnik</th>
							<th>Prvi nadređeni djelatnik</th>
							<th>Drugi nadređeni djelatnik</th>
                            <th class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($works as $work)
                            <tr>
                                <td>{{ $work->odjel }}</td>
                                <td>{{ $work->naziv }}</td>
								<td>{{ $work->job_description }}</td>
								<td>{{ $work->pravilnik }}</td>
								<td>{{ $work->tocke }}</td>
								<td>{{ $work->nadredjeni['first_name'] . ' ' . $work->nadredjeni['last_name'] }}</td>
								<td>{{ $work->prvi_nadredjeni['first_name'] . ' ' . $work->prvi_nadredjeni['last_name'] }}</td>
								<td>{{ $work->drugi_nadredjeni['first_name'] . ' ' . $work->drugi_nadredjeni['last_name'] }}</td>
                                  <td>
                                    <a href="{{ route('admin.works.edit', $work->id) }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    <a href="{{ route('admin.works.destroy', $work->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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
