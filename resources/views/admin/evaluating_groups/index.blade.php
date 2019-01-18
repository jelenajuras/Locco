@extends('layouts.admin')

@section('title', 'Kategorije ocjenjivanja')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.evaluating_groups.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Kategorije ocjenjivanja</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($evaluatingGroups) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Anketa</th>
								<th>Naziv kategorije</th>
								<th>Koeficijent</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($evaluatingGroups as $evaluatingGroup)
								<tr>
									<td>{{ $evaluatingGroup->questionnaire['naziv'] }}</td>
									<td><a href="{{ route('admin.evaluating_groups.show', ['id' => $evaluatingGroup->id ]) }}">{{ $evaluatingGroup->naziv }}</a></td>
									<td>{{ $evaluatingGroup->koeficijent }}</td>
									<td>
										<a href="{{ route('admin.evaluating_groups.edit', $evaluatingGroup->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										<a href="{{ route('admin.evaluating_groups.destroy', $evaluatingGroup->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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