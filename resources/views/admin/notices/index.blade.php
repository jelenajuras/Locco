@extends('layouts.admin')

@section('title', 'Obavijesti')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')

<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.notices.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Nova obavijest
            </a>
        </div>
        <h1>Obavijesti zaposlenicima</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($notices) > 0)
					 <table id="table_id" class="display" style="width: 100%;">
						<thead>
							<tr>
								<th>Poslao</th>
								<th>za</th>
								<th>Subjekt</th>
								<th>Datum</th>
								<th class="not-export-column">Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($notices as $notice)
								<tr>
									<td>{{ $notice->user['first_name'] . ' ' . $notice->user['last_name'] }}</td>
									<td>{{  $notice->department['name'] }}</td>
									<td style="width:25%"><a href="{{ route('admin.notices.show', $notice->id ) }}">{{ $notice->subject }}</a></td>
									<td>{{ date('d.m.Y', strtotime($notice->created_at)) }}</td>
									<td>
										<a href="{{ route('admin.notices.edit', $notice->id) }}">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
										</a>
										<a href="{{ route('admin.notices.destroy', $notice->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
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

<script>
$(document).ready(function() {
  $('#summernote').summernote({
	  height: 200
  });
});
</script>
@stop
