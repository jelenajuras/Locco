@extends('layouts.admin')

@section('title', 'Proizvođači opreme')

@section('content')
<div class="">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.catalog_manufacturers.create',['category_id' =>  $category->id ]) }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novi proizvođač
            </a>
        </div>
        <h2>Kategorija {{ $category->name }} - Proizvođači opreme</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($catalog_manufacturers) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Description</th>
                            <th>URL</th>
                            <th>Email</th>
                            <th>Telefon</th>
							<th>Opcije</th>
                        </tr>
                    </thead>
                     <tbody id="myTable">
					@foreach ($catalog_manufacturers as $catalog_manufacturer) 
                        <tr>
							<td>{{ $catalog_manufacturer->name }}</td>
							<td>{{ $catalog_manufacturer->description }}</td>
							<td><a href="{{ $catalog_manufacturer->url }}" target="_blank" >{{ $catalog_manufacturer->url }}</a></td>
							<td>{{ $catalog_manufacturer->email }}</td>
							<td>{{ $catalog_manufacturer->phone }}</td>
                            <td>
                                <a href="{{ route('admin.catalog_manufacturers.edit', $catalog_manufacturer->id) }}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('admin.catalog_manufacturers.destroy', $catalog_manufacturer->id) }}" class="btn action_confirm" data-method="delete" data-token="{{ csrf_token() }}" >
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
