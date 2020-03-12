@extends('layouts.admin')

@section('title', 'Kandidat')
<style>
#padding {
    padding-left: 1cm;
}
</style>
@section('content')

    <div class="container">
        <div class='btn-toolbar'>
            <a class="btn btn-primary btn-lg" href="{{ url()->previous() }}"  id="stil1">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                Natrag
            </a>
			
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div class="panel-heading">
				<h3>{{ $temporaryEmployee->first_name . ' ' . $temporaryEmployee->last_name }}</h3>
				<br>
				<p><label>Ime oca: </label> {{ $temporaryEmployee->ime_oca }}</p>
				<p><label>Ime majke: </label> {{ $temporaryEmployee->ime_majke }}</p>
				<p><label>OIB: </label> {{ $temporaryEmployee->oib }}</p>
				<p><label>Osobna iskaznica: </label> {{ $temporaryEmployee->oi }}</p>
				<p><label>Datum isteka OI: </label> {!! $temporaryEmployee->oi_istek ? date('d.m.Y', strtotime($temporaryEmployee->oi_istek)) : '' !!}</p>
				<p><label>Datum rodjenja: </label> {!! $temporaryEmployee->datum_rodjenja ? date('d.m.Y', strtotime($temporaryEmployee->datum_rodjenja)) : '' !!}</p>
				<p><label>Mjesto rodjenja: </label> {{ $temporaryEmployee->mjesto_rodjenja }}</p>
				<p><label>Bračno stanje: </label> {{ $temporaryEmployee->bracno_stanje }}</p>
				<p><label>Mobitel: </label> {{ $temporaryEmployee->mobitel }}</p>
				<p><label>Privatan mobitel: </label> {{ $temporaryEmployee->priv_email }}</p>
				<p><label>E-mail: </label> {{ $temporaryEmployee->email }}</p>
				<p><label>Privatan e-mail: </label> {{ $temporaryEmployee->priv_email }}</p>
				<p><label>Prebivalište: </label> {{ $temporaryEmployee->prebivaliste_adresa . ', ' . $temporaryEmployee->prebivaliste_grad  }}</p>
				<p><label>Zvanje: </label> {{ $temporaryEmployee->zvanje }}</p>
				<p><label>Stručna sprema: </label> {{ $temporaryEmployee->sprema }}</p>
				<br>
				<p><label>Datum prijave: </label> {{ date('d.m.Y', strtotime($temporaryEmployee->datum_prijave)) }}</p>
				<p><label>Odjel: </label> {{ $temporaryEmployee->work['odjel'] }}</p>
				<p><label>Radno mjesto: </label> {{ $temporaryEmployee->work['naziv']  }}</p>
				<p><label>Nadređena osoba odjela: </label> {{ $temporaryEmployee->work->department->employee['first_name'] . ' ' . $temporaryEmployee->work->department->employee['last_name']  }}</p>
				<br>
				<p><label>Voditelj radnog mjesta: </label> {{ $temporaryEmployee->work->prvi_nadredjeni['first_name'] . ' ' . $temporaryEmployee->work->prvi_nadredjeni['last_name']  }}</p>
				<p><label>Nadređena osoba radnog mjesta: </label> {{ $temporaryEmployee->work->nadredjeni['first_name'] . ' ' . $temporaryEmployee->work->nadredjeni['last_name']  }}</p>
				@if ($temporaryEmployee->superior_id)
					<p>
						<label>Direktno nadređena osoba: </label> {{ $temporaryEmployee->employee['first_name'] . ' - ' .  $temporaryEmployee->employee['last_name']  }}	
					</p>
				@endif
				<br>
				<p><label>Konfekcijski broj: </label>{{ $temporaryEmployee->konf_velicina }}</p>
				<p><label>Broj cipela: </label>{{ $temporaryEmployee->broj_cipela }}</p>
				<p><label>Napomena: </label> {{ $temporaryEmployee->napomena }}</p>
				<p><label>Odjava: </label>{!! $temporaryEmployee->odjava == 1 ? 'odjavljen' : '' !!}</p>
			</div>           	
        </div>
    </div>
@stop
