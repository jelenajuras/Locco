@extends('layouts.index')

@section('title', 'Prekorad')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
</a>
<div class="">
    <div class="page-header">
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.afterHours.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novi unos
            </a>
        </div>
        <h2>Evidencija prekovremenog rada</h2>
    </div>
	@if(Sentinel::inRole('administrator'))
	<div class="filter">
		<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Traži djelatnika..." title="Upiši ime">
		<input type="text" id="myInput2" onkeyup="myFunction1()" placeholder="od datuma..." title="Upiši početni datum" class="date" >
		<input type="text" id="myInput3" onkeyup="myFunction2()" placeholder="do datuma..." title="Upiši završni datum" class="date" >
		<button onclick="PrintDoc()" >Primjeni Filter</button>
	</div>
	<script type="text/javascript">
		$('.date').datepicker({  
		   format: 'yyyy-mm-dd',
		   startDate:'-60y',
		   endDate:'+1y',
		}); 
	</script> 
	@endif
    <div class="row" id="printarea">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <div class="table-responsive">
				@if(Sentinel::inRole('administrator'))
					@if(count($afterHours) > 0)
						 <table id="table1" class="display" style="width: 100%;">
							<thead >
								<tr>
									<th style="border-bottom: 1px double #ccc;">Djelatnik</th>
									<th style="border-bottom: 1px double #ccc;">Datum</th>
									<th style="border-bottom: 1px double #ccc;">Vrijeme</th>
									<th style="border-bottom: 1px double #ccc;">Napomena</th>
									<th style="border-bottom: 1px double #ccc;">Odobrenje</th>
									<th  style="border-bottom: 1px double #ccc;" class="not-export-column">Opcije</th>
								</tr>
							</thead>
							<tbody id="myTable">
								@foreach ($afterHours as $afterHour)
									<?php
									$vrijeme_1 = new DateTime($afterHour->vrijeme_od);  /* vrijeme od */
									$vrijeme_2 = new DateTime($afterHour->vrijeme_do);  /* vrijeme do */
									$razlika_vremena = $vrijeme_2->diff($vrijeme_1);  /* razlika_vremena*/

									?>

									<tr style="padding: 5px;">
										<td style="border-bottom: 1px solid #ccc;">{{ $afterHour->employee['first_name'] . ' ' . $afterHour->employee['last_name'] }}</td>
										<td style="border-bottom: 1px solid #ccc;">{{ date('Y-m-d', strtotime($afterHour->datum )) }}</td>
										<td style="border-bottom: 1px solid #ccc;">{{ $afterHour->vrijeme_od . '-' . $afterHour->vrijeme_do . '(' .   $razlika_vremena->h . ' h)'   }}</td>
										<td style="border-bottom: 1px solid #ccc;">{{ $afterHour->napomena }}</td>
										<td style="border-bottom: 1px solid #ccc;">{{ $afterHour->odobreno }}</td>
										
										<td style="border-bottom: 1px solid #ccc;">
											<a href="{{ route('admin.afterHours.edit', $afterHour->id) }}" class="btn">
												<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
												
											</a>
											<a href="{{ route('admin.afterHours.destroy', $afterHour->id) }}" class="btn action_confirm {{ ! Sentinel::inRole('administrator') ? 'disabled' : '' }}" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						{{'Nema neodobrenih evidencija!'}}
					@endif
				@endif
				@if($registration->slDani == 1)
					<p class="SLD">Ukupan broj slobodnih dana  {{  $slobodni_dani }}</p>
					<p class="SLD">Iskorišteno slobodnih dana  {{  $koristeni_slobodni_dani }}</p>
					<p class="SLD">Preostali slobodni dani  {{  $slobodni_dani - $koristeni_slobodni_dani }}</p>
				@endif
            </div>
        </div>
    </div>
</div>
<!-- JS Scripts -->
<script>
function myFunction() {
	var input, filter, table, tr, td, i;
	input = document.getElementById("myInput");
	filter = input.value.toUpperCase();
	console.log(filter);
	table = document.getElementById("table1");
	tr = table.getElementsByTagName("tr");

	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[0];
		if (td) {
			if(tr[i].style.display == ""){
				if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		} 	
	}
}

function myFunction1() {
	var input, filter, table, tr, td, i;
	input2 = document.getElementById("myInput2").value;
	console.log(input2);
	filter2 = input2;
	date1 = new Date(filter2);

	table = document.getElementById("table1");
	tr = table.getElementsByTagName("tr");
	for (i = 0; i < tr.length; i++) {
		td2 = tr[i].getElementsByTagName("td")[1];
		if (td2) {
			if(tr[i].style.display == ""){
				date2 = new Date(td2.innerHTML);
				if(date2 >= date1){
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		}
	}
}

function myFunction2() {
	var input, filter, table, tr, td, i;
	input3 = document.getElementById("myInput3").value;
	filter3 = input3;
	date3 = new Date(filter3);
	
	table = document.getElementById("table1");
	tr = table.getElementsByTagName("tr");
	for (i = 0; i < tr.length; i++) {
		td3 = tr[i].getElementsByTagName("td")[1];
		if (td3) {
			if(tr[i].style.display == ""){
				date4 = new Date(td3.innerHTML);
				if(date4 <= date3){
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		}
	}
}

</script>

<script>
function PrintDoc() {

        var toPrint = document.getElementById('printarea');
        var popupWin = window.open('', '_blank');
        popupWin.document.open();
        popupWin.document.write('<!DOCTYPE html><html><head><title>Prokovremeni sati</title></head><body onload="window.print()">')

        popupWin.document.write(toPrint.innerHTML);
        popupWin.document.write('</body></html>');

        popupWin.document.close();

    }
</script>

@stop