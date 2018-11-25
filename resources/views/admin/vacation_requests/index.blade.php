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
							<th onclick="sortTable(6)">Iskorišteni dani  {{ $ova_godina}}</th>
                            <th onclick="sortTable(7)">Neiskorišteno dana  {{ $ova_godina}}</th>
							<th onclick="sortTable(8)">Neiskorišteni slobodni dani</th>
							
                        </tr>
                    </thead>
                    <tbody id="myTable">
						@foreach ($registrations as $registration)
						
							@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
								<?php 
								$employee = Employee::where('id',$registration->employee_id)->first();
								$godisnji = GodisnjiController::godisnji($employee);

								/* Staž Duplico */
								$stazDuplico = GodisnjiController::stazDuplico($registration);
								$godina = $stazDuplico->format('%y');  
								$mjeseci = $stazDuplico->format('%m');
								$dana = $stazDuplico->format('%d');

								/* Staž prijašnji */
								$stazY = 0;
								$stazM = 0;
								$stazD = 0;
								if($registration->staz) {
									$staz = $registration->staz;
									$staz = explode('-',$registration->staz);
									$stazY = $staz[0];
									$stazM = $staz[1];
									$stazD = $staz[2];
								} 
								/* Staž ukupan */
								$danaUk=0;
								$mjeseciUk=0;
								$godinaUk=0;
								
								if(($dana+$stazD) > 30){
									$danaUk = ($dana+$stazD) -30;
									$mjeseciUk = 1;
								}else {
									$danaUk = ($dana+$stazD);
								}
								
								if(($mjeseci+$stazM) > 12){
									$mjeseciUk += ($mjeseci+$stazM) -12;
									$godinaUk = 1;
								}else {
									$mjeseciUk += ($mjeseci+$stazM);
								}
								$godinaUk += ($godina + $stazY);

								$godisnjiUser  = GodisnjiController::godisnjiUser($registration);
								$daniZahtjevi = GodisnjiController::daniZahtjevi($registration);
								?>
								@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
									<tr>
										<td class="show_go"><a href="{{ route('admin.vacation_requests.show', $registration->employee_id) }}" style="width:100%;height:100%;border:none;background-color:inherit;">{{ $registration->employee['last_name']  . ' '. $registration->employee['first_name']}}</a></td>
										<td style="width:10%;">{{ $godina . '-' . $mjeseci . '-' . $dana  }}</td>
										<td style="width:10%;">{{ $godinaUk . '-' . $mjeseciUk . '-' .  $danaUk }}</td>
										<td style="width:10%;"> </td>
										<td style="width:10%;"></td>
										<td style="width:10%;">{{  $godisnjiUser }}</td>
										<td style="width:10%;">{{ $daniZahtjevi }}</td>
										<td style="width:10%;">{{ $godisnjiUser - $daniZahtjevi }}</td>
										<td style="width:10%;"></td>
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