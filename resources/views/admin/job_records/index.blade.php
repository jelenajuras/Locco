@extends('layouts.admin')

@section('title', 'Evidencija rada')

@section('content')
<div class="container-fluid">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.job_records.create') }}" id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novi zapis
            </a>
        </div>
        <h2>Evidencija rada</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($job_records) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Vrijeme</th>
							<th>Zaposlenik</th>
							<th>Odjel</th>
                            <th>Zadatak</th>
                            <th>Voditelj zadatka</th>
							<th>Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
					@foreach ($job_records as $job_record) 
                        <tr>
							<td>{{ $job_record->date }}</td>
							<td>{{ $job_record->time }}</td>
							<td>{{ $job_record->employee['first_name']  . ' ' . $job_record->employee['last_name'] }}</td>
							<td>{{ $job_record->odjel }}</td>
							<td>{{ $job_record->task }}</td>
							<td>{{ $job_record->manager['first_name']  . ' ' . $job_record->manager['last_name']  }}</td>
							<td>
								<a href="{{ route('admin.job_records.edit', $job_record->id) }}">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<a href="{{ route('admin.job_records.destroy', $job_record->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
							
							</td>
                        </tr>
                    @endforeach
					</tbody>
                </table>
			@else
				{{'Nema zapisa!'}}
			@endif
            </div>
        </div>
    </div>
</div>
@stop