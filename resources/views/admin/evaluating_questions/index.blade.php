@extends('layouts.admin')

@section('title', 'Podkategorija')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.evaluating_questions.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Podkategorija ocjenjivanja</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($evaluatingQuestions) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Kategorija</th>
								<th>Podkategorija</th>
								<th>Opis</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($evaluatingQuestions as $evaluatingQuestion)
								<tr>
									<td>{{ $evaluatingQuestion->group['naziv'] }}</td>
									<td>{{ $evaluatingQuestion->naziv }}</td>
									<td>{{ $evaluatingQuestion->opis }}<br><small>{{ $evaluatingQuestion->opis2 }}</small></td>
									<td>
										<a href="{{ route('admin.evaluating_questions.edit', $evaluatingQuestion->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										<a href="{{ route('admin.evaluating_questions.destroy', $evaluatingQuestion->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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