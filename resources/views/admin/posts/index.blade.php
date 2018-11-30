@extends('layouts.admin')

@section('title', 'Poruke')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" />
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
									<?php 
									if($post->to_employee_id == '877282'){
										$to = 'Uprava';
									}elseif($post->to_employee_id == '772864'){
										$to = 'Pravni';
									}elseif($post->to_employee_id == '72286'){
										$to = 'Racunovodstvo';
									}elseif($post->to_employee_id == '48758322'){
										$to = 'IT služba';
									}

									?>
									<td>{!! $to !!}</td>
									<td>
										<a href="{{ route('admin.posts.edit', $post->id)}}">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
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
