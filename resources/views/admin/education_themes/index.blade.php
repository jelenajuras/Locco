@extends('layouts.admin')

@section('title', 'Teme edukacija')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.education_themes.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Teme edukacija</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($educationThemes) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Edukacija</th>
								<th>Naziv teme</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($educationThemes as $educationTheme)
								<tr>
									<td>{{ $educationTheme->education['name'] }}</td>
									<td><a href="{{ route('admin.education_articles.index', ['id' => $educationTheme->id] ) }}">{{ $educationTheme->name }}</a></td>
									<td>
										<a href="{{ route('admin.education_themes.edit', $educationTheme->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										@if(!DB::table('education_articles')->where('theme_id',$educationTheme->id)->first())
											<a href="{{ route('admin.education_themes.destroy', $educationTheme->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
									</td>
								</tr>
							@endforeach	
															
						</tbody>
					</table>
				@else
					{{'Nema unesenih tema!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@stop