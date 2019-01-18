@extends('layouts.admin')

@section('title', 'Ankete')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.questionnaires.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Ankete</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($questionnaires) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Naziv</th>
								<th>Opis</th>
								<th>Status</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($questionnaires as $questionnaire)
								<tr>
									<td><a href="{{ route('admin.questionnaires.show', $questionnaire->id) }}">{{ $questionnaire->naziv }}</a></td>
									<td>{{ $questionnaire->opis }}</td>
									<td>{{ $questionnaire->status }}</td>
									<td>
										<a href="{{ route('admin.questionnaires.edit', $questionnaire->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										<a href="{{ route('admin.questionnaires.destroy', $questionnaire->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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