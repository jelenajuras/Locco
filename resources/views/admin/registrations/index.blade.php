@extends('layouts.admin')

@section('title', 'Duplico djelatnici')

<style>
#padding1 {
    padding-left: 30px;
}
th {
    font-size: 12px;
} 
td {
    font-size: 14px;
} 
input {
	border: 1px solid;
	border-color: d9d9d9;
	border-radius: 3px;
	padding: 3px;
}
</style>

@section('content')
<div class="">
        <h1>Prijavljeni radnici</h1>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive" id="tblData">
			@if(count($registrations) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
							<th width="100" onclick="sortTable(0)">Ime i prezime</th>
							<th width="80" onclick="sortTable(1)">Datum prijave</th>
                            <th width="80" onclick="sortTable(2)">Datum rođenja</th>
							<th width="150" onclick="sortTable(3)">Radno mjesto</th>
							<th width="150" onclick="sortTable(4)">Nadređeni djelatnik</th>
							<th width="150" onclick="sortTable(5)">
								Obračun<br>prokovremenih
							</th>
							<th>Stranac</th>
                            <th width="150" class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
					<?php 
					$i = 0;
					?>
						@foreach ($registrations as $registration)
							
						@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
                            <tr>
								<td>
									<a href="{{ route('admin.registrations.show', $registration->id) }}">
										{{ $registration->employee['last_name']  . ' '. $registration->employee['first_name']}}
									</a>
								</td>
                                <td>{{ date('d.m.Y.', strtotime($registration->datum_prijave)) }}</td>
								<td>{{ date('d.m.Y.', strtotime($registration->employee['datum_rodjenja'])) }}</td>
								<td>{{ $registration->work['odjel'] . ' - ' . $registration->work['naziv'] }}</td>
								<td>{{ $registration->superior['last_name'] . ' ' . $registration->superior['first_name'] }}</td>
								<td>{!! $registration->slDani === 1 ? 'Slobodan dan' : 'Isplata' !!}
								</td>
								<td>{!! $registration->stranac === 1 ? 'Stranac' : '' !!} 
								</td>
								<td>
									@if(Sentinel::inRole('administrator'))
										<a class="btn btn-block" role="button" data-toggle="collapse" href="#collapseExample{{$i}}" aria-expanded="false" aria-controls="collapseExample{{$i}}" id="stil1">
										Opcije
										</a>
										
										<div class="collapse" id="collapseExample{{$i}}">
											<a href="{{ route('admin.employees.edit', $registration->employee_id) }}" class="btn btn-block">
												<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
												Ispravi opće podatke
											</a>
											<a href="{{ route('admin.registrations.edit', $registration->id) }}" class="btn  btn-block">
												<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
												Ispravi podatke o prijavi
											</a>
											<a href="{{ route('admin.employee_equipments.create', ['id' => $registration->employee_id]) }}" class="btn  btn-md btn-block">
												Zaduži opremu
											</a>
											<a href="{{ route('admin.employee_equipments.show', ['id' => $registration->employee_id]) }}" class="btn btn-md btn-block  {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}">
												<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
												Zaduženje
											</a>
										</div>
									@endif
								</td>	
							</tr>
							<?php $i++ ?>
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
