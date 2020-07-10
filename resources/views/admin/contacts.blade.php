@extends('layouts.admin')

@section('title', 'Kontakti djelatnika')

<style>
#padding1 {
    padding-left: 30px;
}
th {
    font-size: 11px;
} 
td {
    font-size: 12px;
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
    <h1>Kontakti</h1>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-lg-offset-2">
            <div class="table-responsive" id="tblData">
			@if(count($registrations) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
							<th width="{!! Sentinel::inRole('administrator') ? '20%' : '25%' !!}" onclick="sortTable(0)">Ime i prezime</th>
							<th width="{!! Sentinel::inRole('administrator') ? '15%' : '25%' !!}" onclick="sortTable(1)">Telefon</th>
							<th width="{!! Sentinel::inRole('administrator') ? '15%' : '25%' !!}" onclick="sortTable(2)">Privatan telefon</th>
                            <th width="{!! Sentinel::inRole('administrator') ? '25%' : '25%' !!}" onclick="sortTable(3)" >E-mail</th>
                            @if (Sentinel::inRole('administrator'))
                                <th width="{!! Sentinel::inRole('administrator') ? '25%' : '25%' !!}" onclick="sortTable(4)" >Prebivalište</th> 
                            @endif
                        </tr>
                    </thead>
                    <tbody id="myTable">					
						@foreach ($registrations as $registration)						
                            <tr>
								<td>{{ $registration->employee['last_name']  . ' '. $registration->employee['first_name']}}</td>
                                <td>{{ $registration->employee->mobitel }}</td>
                                <td>{{ $registration->employee->priv_mobitel }}</td>
                                <td style="text-align: left">{{ $registration->employee->email }}</td>
                                @if (Sentinel::inRole('administrator'))
                                    <td style="text-align: left">{{ $registration->employee->prebivaliste_adresa . ', ' . $registration->employee->prebivaliste_grad }}</td> 
                                @endif
							</tr>
                         @endforeach
                         @foreach ($temporary_Employees as $temporary_Employee)						
                            <tr>
								<td>{{ $temporary_Employee->last_name  . ' '. $temporary_Employee->first_name }}</td>
                                <td>{{ $temporary_Employee->mobitel }}</td>
                                <td>{{ $temporary_Employee->priv_mobitel }}</td>
                                <td style="text-align: left">{{ $temporary_Employee->email }}</td>
                                @if (Sentinel::inRole('administrator'))
                                    <td style="text-align: left">{{ $registration->prebivaliste_adresa . ', ' . $registration->prebivaliste_grad }}</td> 
                                @endif
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
