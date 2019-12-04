@extends('layouts.admin')

@section('title', 'Najave')
<link rel="stylesheet" href="{{ URL::asset('css/notices.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="noticeBoard">
	<a class="gumb_arhiva" href="{{ route('admin.notices.index') }}">Arhiva</a>
	<h1>Najava aktivnosti</h1>
	<section class="notices">
		@if(count($notices)> 0)
			@foreach($notices as $notice)
				<article class="notice" style="text-align:left;">
					<h3>{{ $notice->subject }}</h3>
					<p >{!! $notice->notice !!}</p>
				</article>
			@endforeach	
		@else
			Nema novih obavijesti!
		@endif
	</section>
</div>
<script>
$( document ).ready(function() {
  $('.notice span').css("font-size","16");
  $('.notice span').css("font-family","Arial");
  $('.notice span').css("line-height:","20px");
});
</script>
@stop
