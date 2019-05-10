@extends('layouts.admin')

@section('title', 'Kategorije oglasa')

@section('content')
<div class="container-fluid">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.ad_categories.create') }}" id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Nova kategorija
            </a>
        </div>
        <h2>Kategorije oglasa</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($adCategories) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            @if(Sentinel::inRole('administrator'))
								<th>Opcije</th>
							@endif
                        </tr>
                    </thead>
                    <tbody id="myTable">
					@foreach ($adCategories as $adCategory) 
                        <tr>
							<td><a href="{{ route('admin.ads.index', ['id' => $adCategory->id] ) }}">{{ $adCategory->name }}</a></td>
                             @if(Sentinel::inRole('administrator'))
								<td>
									<a href="{{ route('admin.ad_categories.edit', $adCategory->id) }}">
										<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</a>
									@if(! $ads->where('category_id', $adCategory->id)->first())
										<a href="{{ route('admin.ad_categories.destroy', $adCategory->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
											<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										</a>
									@endif
								</td>
							@endif
                        </tr>
                    @endforeach
					</tbody>
                </table>
			@else
				{{'Nema unesenih kategorija!'}}
			@endif
            </div>
        </div>
    </div>
</div>
@stop