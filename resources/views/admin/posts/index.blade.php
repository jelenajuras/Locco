@extends('layouts.admin')

@section('title', 'Poruke')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
</a>
<div class="" >
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <a class="btn btn-primary btn-lg" href="{{ route('admin.posts.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Pošalji poruku
            </a>
        </div>
        <h1>Poruke</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($posts) > 0)
					<table id="table_id" class="display" style="width: 100%;">
						<thead>
							<tr>
								<th>Title</th>
								<th>Šalje</th>
								<th>Prima</th>
								<th class="not-export-column">Options</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($posts as $post)
								<tr>
									<td>
										<a href="{{ route('admin.posts.show', $post->id) }}">
											{{ $post->title }}
										</a>
									</td>
									<td>{{ $post->user['first_name'] . ' ' . $post->user['last_name']}}</td>
									<td>{!! $post->to_employee['first_name'] == '' ? 'Uprava' : $post->to_employee['first_name'] . ' ' . $post->to_employee['last_name'] !!}</td>
									  <td>
										<a href="{{ route('admin.posts.edit', $post->id) }}" class="{{ Sentinel::getUser()->id != $post->employee_id ? 'disabled' : '' }}">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
										</a>
										<a href="{{ route('admin.posts.destroy', $post->id) }}" class="action_confirm  {{ Sentinel::getUser()->id != $post->employee_id ? 'disabled' : '' }}" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					{{'Nema poruka!'}}
				@endif
            </div>
			
        </div>
    </div>
</div>
@stop
