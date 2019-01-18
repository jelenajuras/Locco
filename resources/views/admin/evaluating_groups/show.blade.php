@extends('layouts.admin')

@section('title', 'Grupe')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}" type="text/css" >
@section('content')
	<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
	</a>
<section>
	<div class="ankete">
		<h2>Grupa: {{ $evaluatingGroup->naziv }}</h2>

		<div class="kategorije">
			@if(count($evaluatingQuestions))
				@foreach($evaluatingQuestions as $evaluatingQuestion)
					<h4>{{ $evaluatingQuestion->naziv}} <small>(Opis: {{ $evaluatingQuestion->opis }} )</small></h4>
					
				@endforeach
			@else
				Nema dodijeljenih grupa
			@endif
		
		</div>
	</div>
</div>
@stop