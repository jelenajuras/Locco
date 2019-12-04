@extends('layouts.admin')

@section('title', 'Kategorije opreme')

@section('content')
<div class="">
     <div class="page-header">
        <a class="btn btn-md pull-left" href="{{ url()->previous() }}">
            <i class="fas fa-angle-double-left"></i>
            Natrag
        </a>
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.catalog_categories.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Nova kategorija
            </a>
        </div>
        <h2>Kategorije opreme</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($catalog_categories) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Description</th>
                            <th></th>
                           
							<th>Opcije</th>
                        </tr>
                    </thead>
                     <tbody id="myTable">
					@foreach ($catalog_categories as $catalog_category) 
                        <tr>
							<td><a href="{{ route('admin.catalog_manufacturers.index', ['id' => $catalog_category->id]) }}">{!! $catalog_category->name !!}</a></td>
							<td>{!! $catalog_category->description !!}</td>
							<td></td>
                            <td>
                                <a href="{{ route('admin.catalog_categories.edit', $catalog_category->id) }}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('admin.catalog_categories.destroy', $catalog_category->id) }}" class="btn action_confirm" data-method="delete" data-token="{{ csrf_token() }}" >
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
					</tbody>
                </table>
			@else
				{{'Nema unesenih podataka!'}}
			@endif
            </div>
        </div>
    </div>
</div>
@stop
