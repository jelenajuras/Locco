@extends('layouts.admin')

@section('title', 'Zahtjevi')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>

<div class="">
     <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.vacation_requests.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novi zahtjev
            </a>
        </div>
        <h2>Godišnji odmori i izostanci</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php 
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y')-1;
		?>
            <div class="table-responsive" id="tblData">
			@if(count($registrations) > 0)
			<!--	<p>Prikaz kolona</p>
				<div class="prikaz">
					<a class="toggle-vis" data-column="0">Ime i prezime</a>
					<a class="toggle-vis" data-column="1">Staž Duplico</a>
					<a class="toggle-vis" data-column="2">Staž ukupno</a>
					<a class="toggle-vis" data-column="3">Dani GO  {{ $prosla_godina }}</a>
					<a class="toggle-vis" data-column="4">Iskorišteni {{ $prosla_godina }}</a>
					<a class="toggle-vis" data-column="5">Dani GO {{ $ova_godina}}</a>
					<a class="toggle-vis" data-column="6">Iskorišteni dani {{ $ova_godina}}</a>
					<a class="toggle-vis" data-column="7">Neiskorišteno dana {{ $ova_godina}}</a>
					<a class="toggle-vis" data-column="8">Slobodni dani</a>
				</div>-->
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
							<th onclick="sortTable(0)">Ime i prezime</th>
							<th onclick="sortTable(1)">Staž Duplico <br>[g-m-d]</th>
							<th onclick="sortTable(2)">Staž ukupno <br>[g-m-d]</th>
							<th onclick="sortTable(3)">Dani GO {{ $prosla_godina }}</th>
							<th  onclick="sortTable(4)">Iskorišteni dani {{ $prosla_godina }}</th>
							<th onclick="sortTable(5)">Dani GO  {{ $ova_godina}}</th>
							<th onclick="sortTable(6)">Iskorišteni dani  {{ $ova_godina}}</th>
                            <th onclick="sortTable(7)">Neiskorišteno dana  {{ $ova_godina}}</th>
							<th onclick="sortTable(8)">Neiskorišteni slobodni dani</th>
							
                        </tr>
                    </thead>
                    <tbody id="myTable">
						@foreach ($registrations as $registration)
							@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
							<?php 
						/* Staž prijašnji */
								$stažY = 0;
								$stažM = 0;
								$stažD = 0;
								if($registration->staz) {
									$staž = $registration->staz;
									$staž = explode('-',$registration->staz);
									$stažY = $staž[0];
									$stažM = $staž[1];
									$stažD = $staž[2];
								}
								
						/* Staž Duplico */	
								$stažDuplico = 0;
								$datum_1 = new DateTime($registration->datum_prijave);  /* datum prijave */
								$stažDuplico = $datum_1->diff($datum);  /* staž u Duplicu*/

								$godina = $stažDuplico->format('%y');  
								$mjeseci = $stažDuplico->format('%m');
								$dana = $stažDuplico->format('%d');
								
						/* Staž ukupan */
								$danaUk=0;
								$mjeseciUk=0;
								$godinaUk=0;
								
								if(($dana+$stažD) > 30){
									$danaUk = ($dana+$stažD) -30;
									$mjeseciUk = 1;
								}else {
									$danaUk = ($dana+$stažD);
								}
								
								if(($mjeseci+$stažM) > 12){
									$mjeseciUk += ($mjeseci+$stažM) -12;
									$godinaUk = 1;
								}else {
									$mjeseciUk += ($mjeseci+$stažM);
								}
								$godinaUk += ($godina + $stažY);

							/* Godišnji odmor - dani*/
			
			
							$GO = 20;
							$GO += (int)($godinaUk/ 4) ;
							
							If($GO > 25){
								$GO = 25;
							} else {
								$GO = $GO;
							}
			
						/* Staž prošle godine */
								$datumPG = new DateTime($prosla_godina .'-12-31');    /* zadnji dan prošle godine */
								$godina_prijave = date_format($datum_1,'Y');  /* godina prijave */
								
								$danaPG = 0;
								$mjeseciPG =0;
								$godinaPG = 0;
								$danaUk_PG=0;
								$mjeseciUk_PG=0;
								$godinaUk_PG=0;
									
								if($godina_prijave < $ova_godina){
									$stažDuplicoPG = $datum_1->diff($datumPG);  /* staž u Duplicu do 31.12*/
									$godinaPG = $stažDuplicoPG->format('%y');  
									$mjeseciPG = $stažDuplicoPG->format('%m');
									$danaPG = $stažDuplicoPG->format('%d');
								}
								
						/* Staž ukupan do 31.12.*/
								if(($danaPG+$stažD) > 30){
									$danaUk_PG = ($danaPG+$stažD) -30;
									$mjeseciUk_PG = 1;
								} else {
									$danaUk_PG = ($danaPG+$stažD);
								}
								
								if(($mjeseciPG+$stažM) > 12){
									$mjeseciUk_PG += ($mjeseciPG+$stažM) -12;
									$godinaUk_PG = 1;
								} else {
									$mjeseciUk_PG += ($mjeseciPG+$stažM);
								}
								$godinaUk_PG += ($godinaPG + $stažY);
								$GO_PG = 20;
								$GO_PG += (int)($godinaUk_PG/ 4) ;
							
								If($GO_PG > 25){
									$GO_PG = 25;
								} else {
									$GO_PG = $GO_PG;
								}

						/* Zahtjevi */		
								$zahtjevi = $requests->where('employee_id',$registration->employee_id);
								
						/* ukupno iskorišteno godišnji zaposlenika*/
								$ukupnoGO = 0;
								$ukupnoGO_PG = 0;
								$dan_SLD = 0;
								foreach($zahtjevi as $zahtjev){
									$begin = new DateTime($zahtjev->GOpocetak);
									$end = new DateTime($zahtjev->GOzavršetak);
									$end->setTime(0,0,1);
									$interval = DateInterval::createFromDateString('1 day');
									$period = new DatePeriod($begin, $interval, $end);
									if($zahtjev->zahtjev == 'GO' & $zahtjev->odobreno == 'DA' ){
										foreach ($period as $dan) {
											if(date_format($dan,'N') < 6 ){
												$ukupnoGO += 1;
												$ukupnoGO_PG += 1;
											}
											if(date_format($dan,'N') < 6 & date_format($dan,'d') == '01' & date_format($dan,'m') == '01' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '06' & date_format($dan,'m') == '01' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '01' & date_format($dan,'m') == '05' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '22' & date_format($dan,'m') == '06' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '25' & date_format($dan,'m') == '06' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '15' & date_format($dan,'m') == '08' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '05' & date_format($dan,'m') == '08' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '08' & date_format($dan,'m') == '10' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '01' & date_format($dan,'m') == '11' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '25' & date_format($dan,'m') == '12' ||
												date_format($dan,'N') < 6 & date_format($dan,'d') == '26' & date_format($dan,'m') == '12'){
													if(date_format($dan,'Y') == $ova_godina ){
														$ukupnoGO -= 1;
													} elseif(date_format($dan,'Y') == $prosla_godina ){
														$ukupnoGO_PG -= 1;
													}
													
											}
											if(date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018' ||
												date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018'){
												$ukupnoGO -= 1;
											}
										}
									}
									if($zahtjev->zahtjev == 'SLD' & $zahtjev->odobreno == 'DA'){
										$dan_SLD += 1;
									}
										
								}
								
								if($prosla_godina == '2017'){
									$GO_PG = 0;
									$ukupnoGO_PG = 0;
								}
								
								$prekovremeniEmpl = $prekovremeni->where('employee_id',$registration->employee_id);
								
								$razlika =0;
								foreach($prekovremeniEmpl as $prekovremeni){
									$vrijeme_1 = new DateTime($prekovremeni->vrijeme_od);  /* vrijeme od */
									$vrijeme_2 = new DateTime($prekovremeni->vrijeme_do);  /* vrijeme do */
									$razlika_vremena = $vrijeme_2->diff($vrijeme_1);  /* razlika_vremena*/
									$razlika += (int)$razlika_vremena->h;
								}

								if($razlika >= 8){
									$razlika = round($razlika / 8, 0, PHP_ROUND_HALF_DOWN);
								} else {
									$razlika =0;
								}
								
								
								$kids = DB::table('kids')->where('employee_id', $registration->employee_id)->get();
								$danGOdijete = 0;
								$djeca = 0;
								foreach($kids as $kid){
									$datum_rodjenja = new DateTime($kid->datum_rodjenja);  /* datum rođenja djeteta */
									$godinaDijete = $datum_rodjenja->diff($datum); 
									if((int)$godinaDijete->y < 7){
										$djeca += 1;
									}
									if($djeca >= 2) {
										$danGOdijete = 1;
									}
								}
								?>
								@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
									<tr>
										<td class="show_go"><a href="{{ route('admin.vacation_requests.show', $registration->employee_id) }}" style="width:100%;height:100%;border:none;background-color:inherit;">{{ $registration->employee['last_name']  . ' '. $registration->employee['first_name']}}</a></td>
										<td style="width:10%;">{{ $godina . '-' . $mjeseci . '-' . $dana }}</td>
										<td style="width:10%;">{{ $godinaUk . '-' . $mjeseciUk . '-' . $danaUk }}</td>
										<td style="width:10%;">{{ $GO_PG }}</td>
										<td style="width:10%;">{{ $ukupnoGO_PG }}</td>
										<td style="width:10%;">{{ $GO }}</td>
										<td style="width:10%;">{{ $ukupnoGO }}</td>
										<td style="width:10%;">{{ $GO - $ukupnoGO}}</td>
										<td style="width:10%;">{{ $razlika - $dan_SLD  }}</td>
									</tr>
								@endif
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