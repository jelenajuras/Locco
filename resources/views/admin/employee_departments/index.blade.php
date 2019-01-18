@extends('layouts.admin')

@section('title', 'Zaposlenici po odjelima')
<?php 
	use App\Models\Employee_department; 
?>
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
           <a class="btn btn-primary btn-lg" href="{{ route('admin.employee_departments.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Ispravi
            </a>
        </div>
	    <h1>Zaposlenici po odjelima</h1>
    </div>
	<input class='pull-right' type="text" id="trazi" onkeyup="TraziIme()" placeholder="Traži ime..." title="Type in a name">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($registrations) > 0)
					 <table id="" class="tableDep" style="width: 100%;">
						<thead>
							<tr>
								<th>Ime i prezime</th>
								<th colspan="18">Odjeli</th>
							</tr>
						</thead>
						<tbody id="myTable1">
							@foreach ($registrations as $registration)
								@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
								<?php
									$employee_departments = Employee_department::where('employee_id',$registration->employee_id)->get();
									
								?>
									<tr>
										<td>{{ $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] }}</td>
										
										<td>
											@if($employee_departments)
												@foreach ($employee_departments as $employee_department)
													<span style="padding: 0 10px;">{{' '.  $employee_department->department['name'] }}</span>
												@endforeach
											@endif	
										</td>
									</tr>
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

<script>
function TraziIme() {
  // Declare variables 
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("trazi");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable1");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
</script>
@stop
