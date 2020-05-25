@extends('layouts.admin')

@section('title', 'Zahtjevi')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
<?php 
	use App\Http\Controllers\GodisnjiController;
?>
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="table_vacation">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg vacation_new" href="{{ route('admin.vacation_requests.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novi zahtjev
			</a>
			<button class="show_ex gumb_stil2" >Prikaži sve</button>
        </div>
        <h2>Godišnji odmori i izostanci</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive" id="tblData">
			@if(count($registrations) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
							<th class="not_align">Ime i prezime</th>
							<th class="not_align">Odjel</th>
							<th >Staž Duplico <br>[g-m-d]</th>
							<th >Staž ukupno <br>[g-m-d]</th>
							@foreach ($godine as $godina)
								@if( $godina == $ova_godina || $godina == $prosla_godina)
									@if ( $godina == $ova_godina) 
										<th>Ukupno GO <br>{{ $godina }}</th>
									@endif
									<th>Razmjerno GO <br>{{ $godina }}</th>
									<th >Iskorišteni dani <br>{{ $godina }}</th>
								@endif
							@endforeach
							<th >Ukupno neiskorišteno <br>dana  GO</th>
							@if(Sentinel::inRole('administrator'))
								<th >Ukupno prekovremenih sati <br></th>
								<th >Ukupno izlazaka <br>sati [dana]</th>
								<th >Ukupno slobodnih <br>dana</th>
								<th >Korišteno slobodnih <br>dana</th>
							@endif
							<th >Neiskorišteni <br>slobodni <br>dani</th>							
                        </tr>
                    </thead>
                    <tbody id="myTable">
						@foreach ($registrations as $registration)

							<?php 
								$prijenos_zahtjeva = 0;								
								/* Staž Duplico */
								$stazDuplico = GodisnjiController::stazDuplico($registration);
								$godina = $stazDuplico->format('%y');  
								$mjeseci = $stazDuplico->format('%m');
								$dana = $stazDuplico->format('%d');
								
								$stazUkupno = GodisnjiController::stazUkupno($registration);
								$godinaUk = $stazUkupno[0];  
								$mjeseciUk = $stazUkupno[1];
								$danaUk = $stazUkupno[2];

								$razmjeranGO = GodisnjiController::razmjeranGO($registration);  //razmjeran GO ova godina								
								$zahtjeviSveGodine = GodisnjiController::zahtjeviSveGodine($registration); // zahtjevi dani za godinu
								$slDani = GodisnjiController::prekovremeni_bez_izlazaka($registration);
								$koristeni_slDani = GodisnjiController::koristeni_slobodni_dani($registration);		
								$izlasci_ukupno = GodisnjiController::izlasci_ukupno($registration);	
								$prekovremeni_sati = round(GodisnjiController::prekovremeni_sati( $registration ),0,1);
								$ukupno_GO = 0;
								$ukupnoDani = 0;
							?>
								<tr {!! DB::table('employee_terminations')->where('employee_id', $registration->employee_id)->first() ? 'class="employee_ex"': '' !!} >
									<td class="show_go not-align">
										<a href="{{ route('admin.vacation_requests.show', $registration->employee_id) }}" style="width:100%;height:100%;border:none;background-color:inherit;color:blue">
											{{ $registration->employee['last_name']  . ' '. $registration->employee['first_name']}}
										</a>
									</td>
									<td class="not-align">{{  $registration->work['odjel'] }}</td>
									<td>{{ $godina . '-' . $mjeseci . '-' . $dana  }}</td> 												<!-- staž Duplico -->
									<td>{{ $godinaUk . '-' . $mjeseciUk . '-' .  $danaUk }}</td>										<!-- Ukupan staž -->
									@foreach ($godine as $godina)
										@php
											$razmjeranGO_PG = GodisnjiController::razmjeranGO_PG($registration, $godina); // razmjerni dani zadana godina
											if ($godina == $prosla_godina && date('n') < 7) {   //  ako je danas mjesec manji od 7
												$ukupno_GO += $razmjeranGO_PG;
											} elseif ( $godina == $ova_godina ){
												$ukupno_GO += $razmjeranGO_PG;
											}
										
											$daniZahtjeviGodina = GodisnjiController::daniZahtjeviGodina($registration, $godina); // zahtjevi - svi dani za godinu
											
											$daniZahtjeviGodina = $daniZahtjeviGodina + $prijenos_zahtjeva;
											$prijenos_zahtjeva = 0;
											if($daniZahtjeviGodina > $razmjeranGO_PG ) {
												$prijenos_zahtjeva = $daniZahtjeviGodina - $razmjeranGO_PG;
											} else {
												$prijenos_zahtjeva = 0;
											} 
											if ( $godina == $ova_godina ||$godina == $prosla_godina  ){
												$ukupnoDani += count ($zahtjeviSveGodine[$godina]);
											}
										@endphp
										@if( $godina == $ova_godina || $godina == $prosla_godina)
											@if ($godina == $prosla_godina)
												<td class="1">{{ $razmjeranGO_PG }}</td>
											@else
												<td>{{GodisnjiController::godisnjiUser($registration) }}</td>
												<td class="2">{{ $razmjeranGO  }}</td>												
											@endif
											<td class="3">{{ count ($zahtjeviSveGodine[$godina] ) }}</td>	
										@endif																				
									@endforeach
									<td class="width_10">{{ $ukupno_GO - $ukupnoDani }}</td> <!-- Neiskorišteni dani GO -->
									@if(Sentinel::inRole('administrator'))
										<td >{{ $prekovremeni_sati }}</td> <!-- Prekovremeni sati  -->
										<td class="width_10">{{ $izlasci_ukupno }} <br> [ {{ round(strstr($izlasci_ukupno, ':', true) /8, 0, PHP_ROUND_HALF_DOWN) }} ]</td> <!-- Izlasci -->
										<td >{{ $slDani }} </td> <!--Ukupno slobodnih dana -->
										<td >{{ $koristeni_slDani }} </td> <!-- korištenih slobodnih dana -->
									@endif
									<td class="width_10">@if($registration->slDani == 1){{ $slDani - $koristeni_slDani  }}@endif</td><!-- Neiskorišteni slobodni dani -->
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
<script>
	$('.show_ex').click(function(){
		$('.employee_ex').toggle();
	});
</script>
<!--
<div class="uputa">
	<p>*** Napomena:</p>
	<p>Sukladno radnopravnim propisima RH:<br>
		- radnik ima za svaku kalendarsku godinu pravo na godišnji odmor od najmanje 20 radnih dana,<br>
		- radnik ima pravo na dodatne dane godišnjeg odmora (po 1 radni dan za svakih navršenih četiri godina <br>radnog staža; po 2 radna dana radniku roditelju s dvoje ili više djece do 7 godina života),<br>
		- ukupno trajanje godišnjeg odmora radnika ne može iznositi više od 25 radnih dana.<br>
		- razmjerni dio godišnjeg odmora za tekuću godinu utvrđuje se u trajanju od 1/12 godišnjeg odmora za <br>svaki mjesec trajanja radnog odnosa u Duplicu u tekućoj godini.<br>

	Za eventualna pitanja, molimo kontaktirati pravni odjel na pravni@duplico.hr.<br>
	</p>
</div>-->
@stop