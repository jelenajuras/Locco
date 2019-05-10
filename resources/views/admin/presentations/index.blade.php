@extends('layouts.admin')

@section('title', 'Prezentacije')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.presentations.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
	    <h1>Prezentacije</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($presentations) > 0)
					<table id="table_id" class="">
						<thead>
							<tr>
								<th>Edukacija</th>
								<th>Tema</th>
								<th>Članak</th>
								<th>Objavio</th>
								<th>Status</th>
								<th>Opcije</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($presentations as $presentation)
								<tr>
									<td>{{ $educations->where('id',$educationTheme->where('id',$presentation->theme_id)->first()->education_id )->first()->name }}</td>
									<td>{{ $presentation->educationTheme['name'] }}</td>
									<td><a href="{{ route('admin.presentations.show', $presentation->id) }}">{{ $presentation->subject }}</a></td>
									<td>{{ $presentation->employee['first_name'] . ' ' .  $presentation->employee['last_name'] }}</td>
									<td>{{ $presentation->status }}</td>
									<td>
										<a href="{{ route('admin.presentations.edit', $presentation->id) }}">
											<i class="fas fa-edit"></i>
										</a>
										<a href="{{ route('admin.presentations.destroy', $presentation->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									</td>
								</tr>
							@endforeach	
						
						</tbody>
					</table>
				@else
					{{'Nema unesenih prezentacija!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@stop