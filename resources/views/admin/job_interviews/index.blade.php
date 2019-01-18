@extends('layouts.admin')

@section('title', 'Razgovori za posao')

@section('content')
<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.job_interviews.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi novi razgovor
            </a>
        </div>
        <h1>Razgovori za posao</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($job_interviews) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Ime i prezime</th>
							<th>Datum</th>
							<th>OIB</th>
                            <th class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($job_interviews as $job_interview)
							@if(!DB::table('employees')->where('first_name',$job_interview->first_name)->where('last_name',$job_interview->last_name)->first() )
								<tr>
									<td>{{ $job_interview->first_name . ' ' . $job_interview->last_name }}</td>
									<td>{{ date('d.m.Y', strtotime($job_interview->datum)) }}</td>
									<td>{{ $job_interview->oib }}</td>
									<td>
										<a href="{{ route('admin.job_interviews.edit', $job_interview->id) }}">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
										</a>
										<a href="{{ route('admin.job_interviews.destroy', $job_interview->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
										<a href="{{ route('admin.employees.create', $job_interview->id) }}" class="btn ">
											<i class="fas fa-check"></i>
											Prijavi radnika
										</a>
									</td>
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
@stop
