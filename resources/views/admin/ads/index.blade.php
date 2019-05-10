@extends('layouts.admin')

@section('title', 'Oglasi')

@section('content')
<div class="container-fluid">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.ads.create') }}" id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novi oglas
            </a>
        </div>
        <h2>Oglasi</h2>
    </div>
    <div class="row ads">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($ads) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Subjekt</th>
                            <th>Oglas</th>
                            <th>Objavio</th>
                            @if(Sentinel::inRole('administrator'))
								<th>Opcije</th>
							@endif
                        </tr>
                    </thead>
                    <tbody id="myTable">
					@foreach ($ads as $ad) 
                        <tr>
							<td>{{ $ad->subject }}</td>
							<td>{!! str_limit(strip_tags($ad->description),50) !!}<a href="{{ route('admin.ads.show', $ad->id) }}">prikaži oglas</a></td>
							<td>{{ $ad->employee['first_name'] . ' ' . $ad->employee['last_name']  }}</td>
                             @if(Sentinel::inRole('administrator'))
								<td>
									<a href="{{ route('admin.ads.edit', $ad->id) }}">
										<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</a>
									<a href="{{ route('admin.ads.destroy', $ad->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								</td>
							@endif
                        </tr>
                    @endforeach
					</tbody>
                </table>
			@else
				{{'Nema unesenih oglasa!'}}
			@endif
            </div>
        </div>
    </div>
</div>
@stop