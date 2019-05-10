@extends('layouts.admin')

@section('title', 'Oglas')
<link rel="stylesheet" href="{{ URL::asset('css/ads.css') }}" type="text/css" >
@section('content')
    <section class="oglasnik">
        <header>
			<div class='btn-toolbar'>
				<a class="btn btn-lg" href="{{ url()->previous() }}">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					Natrag
				</a>
			</div>
			<h1>Oglasnik</h1>
			<input type="text" id="myInput" onkeyup="mySearch()" placeholder="Search..." title="Type in...">
			<a class="predaj" href="{{ route('admin.ads.index') }}">Predaj oglas</a>
		</header>
		
		<main class="ads" id="ads">
			@foreach($ads as $ad)
				<?php 
					$path = 'storage/oglas/' . $ad->id . '/';
					if(file_exists($path)) {
						$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
					}
					
				?>
				<section class="ad">
					<header class="ad_head">
						<h4>{{ $ad->subject }}</h4>
					</header>
					<main class="ad_main">
						<p>{!! $ad->description !!}</p>
						<p> 
							@if(isset($docs))
							@foreach($docs as $doc)
								@if(file_exists('storage/oglas/' . $ad->id . '/' . $doc))
								<img src="{{ asset('storage/oglas/' . $ad->id . '/' . $doc) }}" />
								@endif
							@endforeach
							@endif
						</p>
					</main>
					<footer>
						<p class="ad_empl"><small>{{ $ad->employee['first_name'] }} | {{ \Carbon\Carbon::createFromTimeStamp(strtotime($ad->created_at))->diffForHumans() }}</small></p>
					</footer>
				</section>
			@endforeach
		</main>
	</section>
<script>
$( document ).ready(function() {  // filter knowledge base
	$('#filter1').change(function() {
		var trazi = $('#filter1').val().toLowerCase();
		console.log(trazi);
		
		if(trazi == "all"){
			$('.ads > .ad').show();
		} else {
			$('.ads > .ad').filter(function() {
				console.log($(this).find('.ad_head span').text().toLowerCase());
				$(this).toggle($(this).find('.ad_head span').text().toLowerCase().indexOf(trazi) > -1)
			});
		}
	});	
});
</script>
<script>
function mySearch() {
    var input, filter, element1, article, a, b, i, p, p1, txtValue, txtValue1, txtValue2, txtValue3;
    input = $("#myInput");
    filter = input.val().toUpperCase();
    element1 = $("#ads");
    article = element1.find(".ad");
	
	$( article ).each(function() {
		text1 = $( this ).find('h4').text().toUpperCase();
		text2 = $( this ).find('.ad_main p').text().toUpperCase();
		if(text1 != 'undefined' && text2 != 'undefined' ) {
			if (text1.indexOf(filter) > -1 || text2.indexOf(filter) > -1 ) {
				$( this ).show();
			} else {
				$( this ).hide();
			}	
		} else {
			if(text1 != 'undefined' ) {
				if (text1.indexOf(filter) > -1 ) {
					$( this ).show();
				} else {
					$( this ).hide();
				}	
			}
			if(text2 != 'undefined') {
				if (text2.indexOf(filter) > -1 ) {
					$( this ).show();
				} else {
					$( this ).hide();
				}	
			}
		}
	});
}
</script>
@stop
