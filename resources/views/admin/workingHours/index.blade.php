@extends('layouts.admin')

@section('title', 'Evidencija radnog vremena')
<link rel="stylesheet" href="{{ URL::asset('css/work.css') }}" />

@section('content')

<div class="evidencija">
	<h1>Evidencija radnog vremena</h1>
	<div> 
	
	<form accept-charset="UTF-8" role="form" method="get" action="{{ route('admin.workingHours.create') }}">
		<ul>
			<li>Izbor mjeseca</li>
			<li><input class="date date-own form-control" type="text" name="mjesec"></li>
			<button type="submit" value="Odaberi" >Odaberi</button>
			<script type="text/javascript">
			$('.date-own').datepicker({
				minViewMode: 1,
				format: 'm-yyyy'
			});
			</script>
		</ul>

	</form>
	
	</div>
	<!--
	<table class="lista">
			<tr>
				<th class="ime">Prezime i ime</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>

			@foreach($djelatnici as $djelatnik)
			<tr>
				<td class="ime" >{{ $djelatnik->employee['last_name'] . ' ' . $djelatnik->employee['first_name'] }}</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endforeach
		

	</table>
	-->
</div>

@stop