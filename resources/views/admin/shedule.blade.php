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
					<table id="table_id" class="display izvjestaj_mjesec" style="width: 80%;">
						<thead>	
							<tr>
								<th colspan="8">UKUPNO</th>
								<th colspan="8">{{ $mjesec . '-' . $godina }}</th>
							</tr>
							<tr>
								<th class="ime">Prezime i ime</th>
								<th class="">Obračun<br>prekovremenih sati</th>
								<th class="">Preostalo GO</th>
								<th>Prekovremeni sati </th>
								<th>izlasci</th>
								<th>Broj slobodnih dani</th>
								<th>Korišteni SLD</th>
								<th>Preostali SLD</th>
								<th class="">Iskorišten GO<br>{{ $mjesec . '-' . $godina }}</th>
								<th class="">Iskorišteni SLD<br>{{ $mjesec . '-' . $godina }}</th>
								<th class="">Bolovanje<br>{{ $mjesec . '-' . $godina }}</th>
								<th class="">Plaćeni dopust<br>{{ $mjesec . '-' . $godina }}</th>
								<th class="">Prekovremeni sati<br>{{ $mjesec . '-' . $godina }}</th>
								<th class="">Izlasci<br>{{ $mjesec . '-' . $godina }}</th>
								<th>Dani GO prošla godina</th>
								<th>Dani GO ova godina</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach($employees as $djelatnik)
							<?php 
							$time = round((	(int) (substr(GodisnjiController::izlasci_ukupno($djelatnik),0,-2)) / 8), 0 ,PHP_ROUND_HALF_DOWN);

							?>
								@if(!DB::table('employee_terminations')->where('employee_id',$djelatnik->employee_id)->first() )
									<tr>
										<td>{{ $djelatnik->employee['last_name'] . ' ' . $djelatnik->employee['first_name'] }}</td> 
										<td>@if($djelatnik->slDani == 1) Slobodi dani @elseif($djelatnik->slDani == "0") Isplata @endif </td>
										<td>{{ GodisnjiController::razmjeranGO_PG($djelatnik) +  GodisnjiController::razmjeranGO($djelatnik) - GodisnjiController::daniZahtjevi($djelatnik) - GodisnjiController::daniZahtjeviPG($djelatnik)}}</td>
										<td>{{ round(GodisnjiController::prekovremeni_sati($djelatnik),0,1) }}</td>
										<td>{{ floor(GodisnjiController::izlasci_ukupno($djelatnik))}}</td>
										<td>@if($djelatnik->slDani == 1){{ GodisnjiController::prekovremeni_bez_izlazaka($djelatnik)}}@endif</td>
										<td>{{ GodisnjiController::koristeni_slobodni_dani($djelatnik) }}</td>
										<td>@if($djelatnik->slDani == 1){{ GodisnjiController::prekovremeni_bez_izlazaka($djelatnik) - GodisnjiController::koristeni_slobodni_dani($djelatnik) }}@endif</td>
										<td>{{ GodisnjiController::daniZahtjevi_mj($djelatnik, 'GO' ,$mjesec, $godina ) }}</td><!--Iskorišten GO -->
										<td>{{ GodisnjiController::daniZahtjevi_mj($djelatnik, 'SLD' ,$mjesec, $godina ) }}</td>
										<td>{{ GodisnjiController::daniZahtjevi_mj($djelatnik, 'Bolovanje' ,$mjesec, $godina ) }}</td>
										<td>{{ GodisnjiController::daniZahtjevi_mj($djelatnik, 'PL' ,$mjesec, $godina ) }}</td>
										<td>{{ round(GodisnjiController::prekovremeni_satiMj($djelatnik, $mjesec, $godina) , 2)}}</td>
										<td>{{ GodisnjiController::izlasci_Mj($djelatnik, $mjesec, $godina) }}</td>
										<?php 
											$iskorišteno_mj = GodisnjiController::daniZahtjevi_mj($djelatnik, 'GO' ,$mjesec, $godina );
											
											$daniZahtjevi = GodisnjiController::daniZahtjevi($djelatnik);
											$daniZahtjeviPG = GodisnjiController::daniZahtjeviPG($djelatnik);
											$GO_PG = GodisnjiController::razmjeranGO_PG($djelatnik); // razmjerni dani prošla godina
											$neiskPG = 0;
											$neiskPG = $GO_PG - $daniZahtjeviPG;
											
											if($neiskPG > 0){
												if($daniZahtjevi <= $neiskPG) {
													$daniZahtjeviPG += $daniZahtjevi;
													$daniZahtjevi = 0;
												} 
												if($daniZahtjevi > $neiskPG){
													$daniZahtjeviPG += $neiskPG;
													$daniZahtjevi -= $neiskPG;
												}
											}
											$dani_PG = "";
											$dani_OG = "";
											if($iskorišteno_mj > 0) {
												if($iskorišteno_mj <= $daniZahtjevi) {
													$dani_OG = $iskorišteno_mj;
													$dani_PG = 0;
												} else {
													$dani_PG = $iskorišteno_mj - $daniZahtjevi;
													$dani_OG = $daniZahtjevi;
												} 
											}
										?>
										<td>{{ $dani_PG }}</td> <!-- dani GO prošla godina-->
										<td>{{ $dani_OG}}</td> <!-- dani GO ova godina-->
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