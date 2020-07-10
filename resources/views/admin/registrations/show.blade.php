@extends('layouts.admin')

@section('title', 'Zahtjev')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
    <div class="row employee_info">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div class="panel-heading">
				<h3>{{ $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] }}</h3>
				<p><b>ERP_ID: </b>{{ $registration->erp_id }}</p>
				<br>
				<h4>Osobni podaci</h4>
				<p><b>Ime oca, majke: </b>{{ $registration->employee['ime_oca']. ', ' . $registration->employee['ime_majke'] }}</p>
				<p><b>Djevojačko prezime: </b>{{ $registration->employee['maiden_name'] }}</p>
				<p><b>OIB: </b>{{ $registration->employee['oib'] }}</p>
				<p><b>Osobna iskaznica: </b>{{ $registration->employee['oi'] }}</p>
				<p><b>Datum isteka osobne iskaznice: </b>{{ date('d.m.Y', strtotime( $registration->employee['oi_istek'] )) }}</p>
				<p><b>Datum rođenja: </b>{{ date('d.m.Y', strtotime($registration->employee['datum_rodjenja'])) }}</p>
				<p><b>Mjesto rođenja: </b>{{ $registration->employee['mjesto_rodjenja']  }}</p>
				<p><b>Bračno stanje: </b>{{ $registration->employee['bracno_stanje']  }}</p>
				<p><b>Zvanje: </b>{{ $registration->employee['zvanje']  }}</p>
				<p><b>Stručna sprema: </b>{{ $registration->employee['sprema']  }}</p>
				<br>
				<h4>Kontakt</h4>
				<p><b>Mobitel: </b>{{ $registration->employee['mobitel'] }}</p>
				<p><b>Privatan mobitel: </b>{{ $registration->employee['priv_mobitel']}}</p>
				<p><b>E-mail: </b>{{ $registration->employee['email'] }}</p>
				<p><b>Privatan e-mail: </b>{{ $registration->employee['priv_email'] }}</p>
				<br>
				<p><b>Prebivalište: </b>{{ $registration->employee['prebivaliste_adresa']  . ', ' . $registration->employee['prebivaliste_grad']  }}</p>
				@if( $registration->employee['boraviste_adresa']  )
				<p><b>Boravište: </b>{{ $registration->employee['boraviste_adresa'] . ', ' . $registration->employee['boraviste_grad']  }}</p>
				@endif
				<br>
				<h4>Podaci o zaposlenju</h4>
				<p><b>Radno mjesto: </b>{{  $registration->work['odjel'] . ' - '. $registration->work['naziv'] }}</p>
				<p><b>Voditelj odjela: </b>{{  $registration->work->prvi_nadredjeni['first_name'] . ' '. $registration->work->prvi_nadredjeni['last_name'] }}</p>
				<p><b>Nadređena osoba: </b>{{  $registration->work->nadredjeni['first_name'] . ' '. $registration->work->nadredjeni['last_name'] }}</p>
				<p><b>Direktno nadređeni djelatnik: </b>{{ $registration->superior['last_name'] . ' ' . $registration->superior['first_name'] }}</p>
				<br>
				<p><b>Konfekcijski broj: </b>{{ $registration->employee['konf_velicina']  }}</p>
				<p><b>Veličina cipela: </b>{{$registration->employee['broj_cipela']  }}</p>
				<p><b>Staž kod prošlog poslodavca: </b>{{ $registration->staz  }}</p>
				<p><b>Napomena: </b>{{ $registration->napomena }}</p>
				<br>
				
		<!--		<p><b>Djeca mlađa od 15 godina: </b>{{ DB::table('kids')->where('employee_id', $registration->employee_id )->count() }}</p>
				
				@if(DB::table('kids')->where('employee_id', $registration->employee_id )->count())
					@foreach(DB::table('kids')->where('employee_id', $registration->employee_id )->get() as $kid)
					<p id="padding">{{ $kid->ime . ' '.  $kid->prezime . ', '.  date('d.m.Y', strtotime($kid->datum_rodjenja))  }}</p>
					@endforeach
				@endif
				<br>-->
				
				
				@if(Sentinel::inRole('uprava'))
					<p><b>Efektivna cijena sata: </b>@if(isset($effectiveHour->effective_cost)){{  number_format($effectiveHour->effective_cost,2,",",".") . ' kn' }}@endif</p>
					<p><b>Brutto godišnja plaća: </b>@if(isset($effectiveHour->effective_cost)){{  number_format($effectiveHour->brutto,2,",",".") . ' kn'  }}@endif</p>
				@endif
				<p><b>Liječnički pregled: </b>{{ date('d.m.Y', strtotime($registration->employee['lijecn_pregled'] ))  }}</p>
				<p><b>Zaštita na radu: </b>{{ date('d.m.Y', strtotime($registration->employee['ZNR'] ))  }}</p>
				<br>
				@if($registration->stranac== 1)
					<p><b>Istek dozvole za boravak i rad u RH: </b> {{ date('d.m.Y', strtotime( $registration->datum_dozvola ))  }}</p>
				@endif
				<br>
				<h4>Zadužena oprema: </h4>
				@foreach(DB::table('employee_equipments')->where('employee_id', $registration->employee_id )->get() as $oprema)
				<p id="padding">{{ DB::table('equipment')->where('id', $oprema->equipment_id)->value('naziv') . ' - '.  $oprema->kolicina . ' kom'}}
					@if($oprema->datum_povrata)
					{{' - razduženo: ' . $oprema->datum_povrata }}
					@endif
					</p>
				@endforeach
				
			</div>
           	
        </div>

    </div>
	
	</body>
</html>
@stop