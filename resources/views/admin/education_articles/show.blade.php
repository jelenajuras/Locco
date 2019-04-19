@extends('layouts.admin')

@section('title', 'Članak')
<link rel="stylesheet" href="{{ URL::asset('css/education.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<h3>{{ $educationArticle->subject }}</h3>
			{!! $educationArticle->article !!}
	</div>
</div>
@stop