@extends('layouts.admin')

@section('title', 'Članak')
<link rel="stylesheet" href="{{ URL::asset('css/education.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<?php 
				
			?>
<div class="post" >
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
	@if($presentations)
		<input type="text" id="myInput" onkeyup="mySearch()" placeholder="Search..." title="Type in...">
		<?php 
			$prezentacije = array();
			$themes = array();
			foreach($presentations as $pres) {
				array_push($prezentacije, $pres->subject);
				array_push($themes,$pres->educationTheme['name'] );
			}
		?>
		<div class="select1">
			<label>Odaberi prezentaciju </label>
			<select class="pres_select" id="filter1">
				<option>all</option>
				@foreach(array_unique($prezentacije) as $prezentacija)
					<option>{{ $prezentacija }}</option>
				@endforeach	
			</select>
		</div>
		<div class="select1">
			<label>Odaberi temu</label>
			<select class="pres_select" id="filter2">
				<option>all</option>
				@foreach(array_unique($themes) as $theme)
					<option>{{ $theme }}</option>
				@endforeach	
			</select>
		</div>
		<div id="articles">
			@foreach($presentations->where('status','aktivan') as $presentation)
				<div class="article panel panel-default">
					<div class="panel-heading">
						<h4>{{ $presentation->educationTheme['name'] }}</h4>
					</div>
					<div class="panel-body">
						<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $presentation->employee['first_name'] . ' ' . $presentation->employee['last_name'] }} | <span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($presentation->created_at))->diffForHumans() }} </small>
						<h4>{{ $presentation->subject }}</h4>
						<div class="panel-body">
							{!! $presentation->article !!}
						</div>	
						
					</div>
				</div>
			@endforeach
		</div>
	@else
		<div class="article panel panel-default">
			<div class="panel-heading">
				<h4>{{ $presentation->educationTheme['name'] }}</h4>
			</div>
			<div class="panel-body">
				<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $presentation->employee['first_name'] . ' ' . $presentation->employee['last_name'] }} | <span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($presentation->created_at))->diffForHumans() }} </small>
				<h4>{{ $presentation->subject }}</h4>
				<div class="panel-body">
					{!! $presentation->article !!}
				</div>	
				
			</div>
		</div>
	@endif
	</div>
</div>
<script>
$( document ).ready(function() {  // filter knowledge base
	$('#filter1').change(function() {
		var trazi = $('#filter1').val().toLowerCase();

		if(trazi == "all"){
			$('#articles > .article').show();
		} else {
			$('#articles > .article').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
			});
		}
	});	
});
</script>
<script>
function mySearch() {
    var input, filter, element1, article, a, b, i, p, p1, txtValue, txtValue1, txtValue2, txtValue3;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    element1 = document.getElementById("articles");
    article = element1.getElementsByClassName("article");
    for (i = 0; i < article.length; i++) {
        a = article[i].getElementsByTagName("h4")[0];
        b = article[i].getElementsByTagName("h4")[1];
        p = article[i].getElementsByTagName("p")[0];
        p1 = article[i].getElementsByTagName("p")[1];
		
		if(a != 'undefined') {
			txtValue = a.textContent || a.innerText;
		}
        if(b != 'undefined') {
			txtValue1 = b.textContent || b.innerText;
		}
		if(p != 'undefined') {
			txtValue2 = p.textContent || p.innerText;
		}

        if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1 ) {
            article[i].style.display = "";
        } else {
            article[i].style.display = "none";
        }
    }
}
</script>

@stop
