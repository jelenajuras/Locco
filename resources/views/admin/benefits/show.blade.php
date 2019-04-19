@extends('layouts.admin')

@section('title', 'Naslovnica')

@section('content')
<div class="container-fluid">
	<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
	</a>
	<div class="benefits">
	<h1>Pogodnosti za zaposlenike</h1>
		@foreach($benefits as $benefit)
			<div class="benefit col-sm-8 col-sm-offset-2">
				<div class="card ">
				  <div class="card-body">
					<h3 class="card-title"><a href="{{ $benefit->url2 }}" target="_blank" class="btn">{{ $benefit->name }}<small> (link na pojašnjenja)</small></a></h3>
					<p class="card-text">{{ $benefit->description }}</p>
					<p class="card-text">{!! $benefit->comment !!}</p>
					<a href="{{ $benefit->url2 }}" target="_blank" class="btn">Link na detaljna pojašnjenja</a>
					<a href="{{ $benefit->url }}" target="_blank" class="btn">Link na pogodnosti</a>
				  </div>
				</div>
			</div>
		@endforeach

	</div>
</div>
@stop
