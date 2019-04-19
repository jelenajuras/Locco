@extends('layouts.admin')

@section('title', 'Edukacija')
<link rel="stylesheet" href="{{ URL::asset('css/education.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.educations.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Edukacije</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($educations) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Naziv</th>
								<th>Odjel</th>
								<th>Status</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($educations as $education)
								<tr>
									<td><a href="{{ route('admin.education_themes.index', ['id' => $education->id] ) }}">{{ $education->name }}</a></td>
									
									<?php  
									$departments_id = '';
										if($education->to_department_id){
											$departments_id = explode(",",$education->to_department_id);
										}
										
									?>
									<td>
										@if($departments_id)
											@foreach($departments_id as $department_id)
												<span style="padding: 0 20px;">{{$departments->where('id', $department_id)->first()->name }}</span>
											@endforeach
										@endif
									
									</td>
									<td>{{ $education->status }}</td>
									<td>
										<a href="{{ route('admin.educations.edit', $education->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										@if(!DB::table('education_themes')->where('education_id',$education->id)->first())
											<a href="{{ route('admin.educations.destroy', $education->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
									</td>
								</tr>
							@endforeach	
															
						</tbody>
					</table>
				@else
					<p>{{'Nema podataka!'}}</p>
				@endif
            </div>
        </div>
    </div>
</div>
@stop