@extends('layouts.admin')

@section('title', 'Oznake radnog vremena')

@section('content')

<div class="container">
    <div class="cont page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.workingTags.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi novu uznaku
            </a>
        </div>
		</br>
        <h1>Oznake radnog vremena</h1>
		<input class="form-control" id="myInput" type="text" placeholder="Traži..">
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($workingTags) > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Naziv</th>
							<th>Broj sati</th>
							<th>Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($workingTags as $workingTag)
                            <tr>
                                <td>{{ $workingTag->naziv }}</td>
                                <td>{{ $workingTag->sati }}</td>
                                <td>
                                    <a href="{{ route('admin.workingTags.edit', $workingTag->id) }}" class="btn btn-default">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        Ispravi
                                    </a>
                                    <a href="{{ route('admin.workingTags.destroy', $workingTag->id) }}" class="btn btn-danger action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        Obriši
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
