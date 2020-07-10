@extends('layouts.admin')

@section('title', 'Evidencija radnog vremena')
<link rel="stylesheet" href="{{ URL::asset('css/work.css') }}" />

@section('content')

<div class="evidencija">
	<h1>Evidencija radnog vremena za {{ $mjesec . '-' . $godina }}</h1>
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.workingHours.store') }}">

	<table class="lista">
			<tr>
				<th class="ime">Prezime i ime</th>
				@foreach($list as $value)
				<?php 
				$dan1 = date('D', strtotime($value));
				
				switch ($dan1) {
					 case 'Mon':
						$dan = 'P';
						break;
					case 'Tue':
						$dan = 'U';
						break;
					case 'Wed':
						$dan = 'S';
						break;
					case 'Thu':
						$dan = 'Č';
						break;
					case 'Fri':
						$dan = 'P';
						break;
					case 'Sat':
						$dan = 'S';
						break;	
					case 'Sun':
						$dan = 'N';
						break;	
				 }
				?>
					<th>{{ date('d', strtotime($value)) .' '. $dan }}</th>
				@endforeach
			</tr>
			
			@foreach($djelatnici as $djelatnik)
				<tr>
					<td>{{ $djelatnik->employee['last_name'] . ' ' . $djelatnik->employee['first_name'] }}</td>
					@for($a = 0; $a < 31; $a++)
						<?php 
							$go = '';
							$zahtjev = DB::table('vacation_requests')->where('employee_id',$djelatnik->id)->where('zahtjev','GO')->where('start_date','2018-09-17');
							if($zahtjev){
								$go='GO';
							}else {
								$go='nema';
							}
							
						?>
						<td class="evid">
						
							{{ $go }}

						</td>
					@endfor
				</tr>
			@endforeach

	</table>
	<!--<input name="_token" value="{{ csrf_token() }}" type="hidden">
    <input class="btn btn-lg btn-primaryk" type="submit" value="Upiši">-->
	</form>
</div>

@stop