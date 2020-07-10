@extends('layouts.admin')

@section('title', 'Izostanci djelatnika' . ' - ' . $temporaryEmployee->first_name . ' ' .  $temporaryEmployee->last_name )
<?php
	use App\Http\Controllers\GodisnjiController;

?>
@section('content')
<div class="row">
		<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
			<i class="fas fa-angle-double-left"></i>
			Natrag
		</a>
    <div class="page-header">
        <h1>Izostanci - {{ $temporaryEmployee->first_name . ' ' .  $temporaryEmployee->last_name }}</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <div class="table-responsive">
				@if(count($requests) > 0)
					<table id="table_id" class="display sort_2_desc" style="width: 100%;">
						<thead>
							<tr>
								<th class="not-export-column">Opcije</th>
								<th>Od</th>
								<th>Do</th>
								<th>Period</th>
								<th>Vrijeme</th>
								<th>Zahtjev</th>
								<th>Napomena</th>
								<th>Odobrio voditelj</th>
								<th>Odobreno</th>
								<th >Odobrio</th>
								<th>Datum odobrenja</th>
							</tr>
						</thead>
						<tbody id="myTable">
						@foreach($requests as $request)
							<?php 
								$brojDana = GodisnjiController::daniGO(['start_date' => $request->start_date, 'end_date' => $request->end_date] );
								$vrijeme = GodisnjiController::izlazak(['od' => $request->start_time, 'do' => $request->end_time] );
							?>
							<tr>
							<tr>
								<td class="not-export-column">
								@if(Sentinel::inRole('administrator'))
									<a href="{{ route('admin.temporary_employee_requests.edit', $request->id) }}" class="width_33">
										<span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
									<a href="{{ route('admin.temporary_employee_requests.destroy', $request->id) }}" class=" action_confirm width_33" data-method="delete" data-token="{{ csrf_token() }}">
										<i class="far fa-trash-alt"></i>
									</a>
								@endif
								</td>
								<td>{{ date('Y.m.d.', strtotime( $request->start_date)) }}</td>
								<td>{{ date('Y.m.d.', strtotime( $request->end_date)) }}</td>
								
								<td>@if($request->zahtjev == 'Izlazak')
										{{$vrijeme . ' h' }}
									@else
										{{$brojDana . ' dana' }}
									@endif
								</td>
								<td>	
									@if($request->zahtjev == 'Izlazak') 
										{{ date('H:i', strtotime($request->start_time))  }} - {{  date('H:i', strtotime($request->end_time)) }}
									@endif
								</td>
								<td>{{ $request->zahtjev }}
									
								</td>
								<td>{{ $request->napomena }}</td>
								<td>{{ $request->odobreno2 }}</td>
								<td>{{ $request->odobreno }}  {{ $request->razlog  }}</td>
								<td>{{ $request->authorized['first_name'] . ' ' . $request->authorized['last_name']}}</td>
								<td>
									@if( $request->datum_odobrenja != "")
									{{ date('d.m.Y.', strtotime( $request->datum_odobrenja))}}
									@endif
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
@stop
