@extends('layouts.index')

@section('title', 'Raspored')
<link rel="stylesheet" href="{{ URL::asset('css/raspored.css') }}" type="text/css">
<?php 
	use App\Http\Controllers\GodisnjiController;
?>
@section('content')
<div class="">
	 <div class='btn-toolbar'>
		<a class="btn btn-md" href="{{ url()->previous() }}">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			Natrag
		</a>
	</div>
	<h1>Evidencija {{ $mjesec . '-' . $godina }}</h1>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="table-responsive">
					<table id="table_id" class="display" style="width: 100%;">
						<thead>	
							<tr>
								<th class="ime">Prezime i ime</th>
								<th class="">Razmjeran GO</th>
								<th class="">Iskorišten GO</th>
								<th class="">Prekovremeni sati</th>
								<th class="">Izlasci</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach($employees as $djelatnik)
							<?php 
							
							?>
							@if(!DB::table('employee_terminations')->where('employee_id',$djelatnik->employee_id)->first() )
									<tr>
										<td>{{ $djelatnik->employee['last_name'] . ' ' . $djelatnik->employee['first_name'] }}</td>
										<td>{{ GodisnjiController::razmjeranGO($djelatnik) }}</td>
										<td>{{ GodisnjiController::daniZahtjevi($djelatnik) }}</td>
										<td>{{ GodisnjiController::prekovremeni_satiMj($djelatnik,$mjesec, $godina) }}</td>
										<td>{{ GodisnjiController::izlasci_Mj($djelatnik,$mjesec, $godina) }}</td>
									</tr>
								@endif
								@endforeach
						</tbody>
					</table>
				</div>	
			</div>
		</div>
</div>


@stop