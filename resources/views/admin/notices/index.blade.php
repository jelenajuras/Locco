@extends('layouts.admin')

@section('title', 'Obavijesti')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')

<div class="">
    <div class="page-header">
        @if(Sentinel::inRole('administrator') || Sentinel::inRole('uprava'))
		<div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.notices.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Nova obavijest
            </a>
        </div>
		@endif
        <h1>Obavijesti zaposlenicima</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($notices) > 0)
					 <table id="table_id" class="display sort_5_desc" style="width: 100%;">
						<thead>
							<tr>
								<th>Poslao</th>
								<th>za</th>
								<th>Tip objave</th>
								<th>Subjekt</th>
								<th>Datum</th>
								@if(Sentinel::inRole('administrator') || Sentinel::inRole('uprava'))<th class="not-export-column">Opcije</th>@endif
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($notices as $notice)
								@php
									$departments_ids = explode(',', $notice->to_department_id);
								@endphp
								@if (isset($employee_departments))
									@foreach ($employee_departments as $employee_department)
										@if(in_array( $employee_department, $departments_ids))
											<tr>
												<td>{{ $notice->user['first_name'] . ' ' . $notice->user['last_name'] }}</td>
												
												<td>
													{{ $notice->department['name'] }}
												</td>
												<td>{{ $notice->type }}</td>
												<td style="width:25%"><a href="{{ route('admin.notices.show', $notice->id ) }}">{{ $notice->subject }}</a></td>
												<td>{{ date('Y.m.d', strtotime($notice->created_at)) }}</td>
												@if(Sentinel::inRole('administrator') || Sentinel::inRole('uprava'))<td>
													<a href="{{ route('admin.notices.edit', $notice->id) }}">
														<i class="far fa-edit"></i>
													</a>
													<a href="{{ route('admin.notices.destroy', $notice->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
														<i class="far fa-trash-alt"></i>
													</a>
												</td>@endif
											</tr>

										@endif
									@endforeach
								@else  <!-- ADMINISTRATOR-->
									<tr>
										<td>{{ $notice->user['first_name'] . ' ' . $notice->user['last_name'] }}</td>
										
										<td>
											@foreach ($departments_ids as $department_id)
												{{ $departments->where('id', $department_id )->first()->name }}
											@endforeach
											
										</td>
										<td>{{ $notice->type }}</td>
										<td style="width:25%"><a href="{{ route('admin.notices.show', $notice->id ) }}">{{ $notice->subject }}</a></td>
										<td>{{ date('Y.m.d', strtotime($notice->created_at)) }}</td>
										@if(Sentinel::inRole('administrator') || Sentinel::inRole('uprava'))<td>
											<a href="{{ route('admin.notices.edit', $notice->id) }}">
												<i class="far fa-edit"></i>
											</a>
											<a href="{{ route('admin.notices.destroy', $notice->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										</td>@endif
									</tr>
								@endif
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
