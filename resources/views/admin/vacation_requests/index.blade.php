@extends('layouts.admin')

@section('title', 'Zahtjevi')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
<?php 
	use App\Http\Controllers\GodisnjiController;
	use App\Models\Employee;
?>
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
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
							<th onclick="sortTable(0)">Ime i prezime</th>
							<th onclick="sortTable(1)">Staž Duplico <br>[g-m-d]</th>
							<th onclick="sortTable(2)">Staž ukupno <br>[g-m-d]</th>
							<th onclick="sortTable(3)">Dani GO {{ $prosla_godina }}</th>
							<th  onclick="sortTable(4)">Iskorišteni dani {{ $prosla_godina }}</th>
							<th onclick="sortTable(5)">Dani GO  {{ $ova_godina}}</th>
							<th onclick="sortTable(6)">Razmjeran dio GO  {{ $ova_godina}}</th>
							<th onclick="sortTable(7)">Iskorišteni dani  {{ $ova_godina}}</th>
                            <th onclick="sortTable(8)">Neiskorišteno dana  {{ $ova_godina}}</th>
							<th onclick="sortTable(9)">Neiskorišteni slobodni dani</th>
							
                        </tr>
                    </thead>
                    <tbody id="myTable">
						@foreach ($registrations as $registration)
							@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
								<?php 
								/* Staž Duplico */
								$stazDuplico = GodisnjiController::stazDuplico($registration);
								$godina = $stazDuplico->format('%y');  
								$mjeseci = $stazDuplico->format('%m');
								$dana = $stazDuplico->format('%d');
								
								$stazUkupno = GodisnjiController::stazUkupno($registration);
								$godinaUk = $stazUkupno[0];  
								$mjeseciUk = $stazUkupno[1];
								$danaUk = $stazUkupno[2];
								
								$godisnjiUser  = GodisnjiController::godisnjiUser($registration);
								
								$daniZahtjevi = GodisnjiController::daniZahtjevi($registration);
								$slDani = GodisnjiController::slobodni_dani($registration);
								$koristeni_slDani = GodisnjiController::koristeni_slobodni_dani($registration);
								
								$razmjeranGO = GodisnjiController::razmjeranGO($registration);
								
								?>
								@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
								
									<tr>
										<td class="show_go"><a href="{{ route('admin.vacation_requests.show', $registration->employee_id) }}" style="width:100%;height:100%;border:none;background-color:inherit;">{{ $registration->employee['last_name']  . ' '. $registration->employee['first_name']}}</a></td>
										<td style="width:10%;">{{ $godina . '-' . $mjeseci . '-' . $dana  }}</td>
										<td style="width:10%;">{{ $godinaUk . '-' . $mjeseciUk . '-' .  $danaUk }}</td>
										<td style="width:10%;"></td>
										<td style="width:10%;"></td>
										<td style="width:10%;">{{  $godisnjiUser }}</td> <!-- ukuno GO -->
										<td style="width:10%;">{{ $razmjeranGO }}</td> <!-- Razmjerni dani GO-->
										<td style="width:10%;">{{ $daniZahtjevi }}</td> <!-- // iskorišteni dani godišnjeg odmora ova godina -->
										<td style="width:10%;">{{ $razmjeranGO - $daniZahtjevi }}</td>  <!-- // neiskorišteni dani godišnjeg odmora ova godina -->
										<td style="width:10%;">{{ $slDani - $koristeni_slDani  }}</td>
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