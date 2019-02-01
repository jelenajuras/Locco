<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('title')</title>
		
		<!--Bootstrap -->
		<link href="node_modules/bootstrap/dist/css/bootstrap.css">
		
		<!--Font awesome -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!--style -->
		<link rel="stylesheet" href="{{ URL::asset('css/_adminPage.css') }}" type="text/css" >
		@stack('stylesheet')
    </head>
    <body>
	@if(Sentinel::check())
	<input type="hidden" id="rola" {!! Sentinel::inRole('basic') ? 'value="basic"' : '' !!} />
		<nav>
			<img src="{{ asset('img/Logo_Duplico1.png') }}" />
			<ul>
				<li><a href="{{ route('home') }}"><i class="fas fa-home"></i>Dashboard</a></li>
				<li><a href="#"><i class="far fa-comment-alt"></i>Message</a></li>
				<li><a href="#"><i class="far fa-calendar-alt"></i>Calendar</a></li>
				<li><a href="#"><i class="fas fa-suitcase"></i>Employees</a></li>
				<li><a href="#"><i class="fas fa-graduation-cap"></i>Knowledge base</a></li>
			</ul>
		</nav>
		<section class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
				@include('notifications')
				@yield('content')
		</section>
     
		<!--Bootstrap -->
		<script href="node_modules/bootstrap/dist/js/bootstrap.js"></script>
		
		<script src="{{ asset('js/nav.js') }}"></script>

		
		@stack('script')
		@endif
    </body>
</html>
