@extends('layouts.admin')

@section('title', 'Naslovnica')
<link rel="stylesheet" href="{{ URL::asset('css/notices.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="noticeBoard">
	<h1>Oglasna ploča</h1>
	<section class="notices">
		@foreach($notices as $notice)
			<article class="notice">
				<h1>{{ $notice->subject }}</h1>
				<p>{{ $notice->notice }}</p>
			</article>
		@endforeach
	</section>
</div>
@stop
