@extends('layouts.admin')

@section('title', 'Radne upute')

@section('content')
<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.instructions.create') }}" id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi novu uputu
            </a>
        </div>
        <h1>Radne upute</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($instructions) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Naslov</th>
							<th>Odjel</th>
							<th>Opis</th>
                            <th class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($instructions as $instruction)
                            <tr>
                                <td><a href="{{ route('admin.instructions.show', $instruction->id) }}">{{ $instruction->title }}</a></td>
								<td>{{ $instruction->department['name'] }}</td>
                                <td>{{ str_limit($instruction->description,100) }}</td>
                                 <td>
                                    <a href="{{ route('admin.instructions.edit', $instruction->id) }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    <a href="{{ route('admin.instructions.destroy', $instruction->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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
