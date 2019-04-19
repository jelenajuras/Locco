@extends('layouts.admin')

@section('title', 'Članak')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.education_articles.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Članak</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($educationArticles) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Edukacija</th>
								<th>Tema</th>
								<th>Članak</th>
								<th>Objavio</th>
								<th>Status</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($educationArticles as $educationArticle)
								<tr>
									<td>{{ $educations->where('id',$educationTheme->where('id',$educationArticle->theme_id)->first()->education_id )->first()->name }}</td>
									<td>{{ $educationArticle->educationTheme['name'] }}</td>
									<td><a href="{{ route('admin.education_articles.show', $educationArticle->id) }}">{{ $educationArticle->subject }}</a></td>
									<td>{{ $educationArticle->employee['first_name'] . ' ' .  $educationArticle->employee['last_name'] }}</td>
									<td>{{ $educationArticle->status }}</td>
									<td>
										<a href="{{ route('admin.education_articles.edit', $educationArticle->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										<a href="{{ route('admin.education_articles.destroy', $educationArticle->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									</td>
								</tr>
							@endforeach	
						
						</tbody>
					</table>
				@else
					{{'Nema unesenih članaka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@stop