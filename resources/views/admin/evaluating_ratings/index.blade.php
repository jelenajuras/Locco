@extends('layouts.admin')

@section('title', 'Ocjene')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.evaluating_ratings.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Ocjene</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($evaluatingRatings) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Opis</th>
								<th>Ocjena</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($evaluatingRatings as $evaluatingRating)
								<tr>
									<td>{{ $evaluatingRating->naziv }}</td>
									<td>{{ $evaluatingRating->rating }}</td>
									<td>
										<a href="{{ route('admin.evaluating_ratings.edit', $evaluatingRating->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										<a href="{{ route('admin.evaluating_ratings.destroy', $evaluatingRating->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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