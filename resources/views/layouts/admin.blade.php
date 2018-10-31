<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('title')</title>

		<!-- Bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<!-- Date picker-->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
		<!-- Awesome icon -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
			
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<link rel="stylesheet" href="{{ URL::asset('css/admin.css') }}" type="text/css" >
		<link rel="stylesheet" href="{{ URL::asset('css/dashboard.css') }}"/>

		<!-- jQuery Timepicker --> 
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
		
		@stack('stylesheet')
    </head>
    <body>
		<header>
			<h1><img src="{{ asset('img/Duplico_logo_white.png') }}" /> portal za zaposlenike</h1>
			<ul class="">
				@if(Sentinel::check())
					<a href="{{ route('auth.logout') }}">Odjava</a></li>
				@else
					<li><a href="{{ route('auth.login.form') }}">Login</a></li>
					<li><a href="{{ route('auth.register.form') }}">Register</a></li>
				@endif
			</ul>
		</header>
		<section class="Body_section">
			<input type="hidden" id="rola" {!! Sentinel::inRole('basic') ? 'value="basic"' : '' !!} />
			<nav class="topnav col-xs-12 col-sm-2 col-md-2 col-lg-2" id="myTopnav">
				@if(Sentinel::check() && Sentinel::inRole('administrator') || Sentinel::inRole('basic') ||  Sentinel::inRole('uprava'))
					<a href="{{ route('home') }}" class="active naslov">Naslovnica</a>
					<a href="{{ route('users.edit', Sentinel::getUser('id')) }}">Ispravi lozinku</a></li>
					@if(Sentinel::inRole('administrator'))
					<a href="{{ route('admin.gantt') }}" >Kalendar</a>
					<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">Dokumenti</a>
					
						<button class="poruke" data-toggle="collapse" data-target="#link1"><span>Opći podaci<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse " id="link1">
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('users.index') }}">Korisnici</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('roles.index') }}">Uloge</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.works.index') }}">Radna mjesta</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.terminations.index') }}">Otkazi</a>
							<a class=" {{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.equipments.index') }}" >Radna oprema</a>
							<a class=" {{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.cars.index') }}">Vozila</a>	
						</div>
						<button class="poruke" data-toggle="collapse" data-target="#link2"><span>Administracija<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse " id="link2">
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.job_interviews.index') }}">Razgovori za posao</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.employees.index') }}">Kandidati za posao</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.registrations.index') }}">Prijavljeni radnici</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.employee_equipments.index') }}">Zadužena oprema</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.kids.index') }}">Djeca zaposlenika</a>
							<a class=" {{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.employee_terminations.index') }}">Odjavljeni radnici</a>
						</div>
						<button class="poruke" data-toggle="collapse" data-target="#link3"><span>Izostanci<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse " id="link3">
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.vacation_requests.index') }}">Zahtjevi za godišnji odmor</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.afterHours.index') }}">Prekovremeni rad</a>
							<a class="" href="{{ route('admin.shedulers.index') }}">Raspored izostanaka</a>	
						</div>
						<button class="poruke" data-toggle="collapse" data-target="#link4"><span>Projekti<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse " id="link4">
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">Klijenti</a>
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">Projekti</a>
						</div>
						<button class="poruke" data-toggle="collapse" data-target="#link5"><span>Ostalo<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse " id="link5">
							<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.notices.index') }}">Obavijesti</a>
							<a class="" href="{{ route('admin.showKalendar') }}">Kalendar sastanaka</a>
							
							@if(Sentinel::inRole('uprava'))
								<a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ route('admin.effective_hours.index') }}">ECH</a>
							@endif
						</div>
					@endif
					@if(DB::table('notices')->take(5)->get())
					<button class="poruke" data-toggle="collapse" data-target="#poruke1"><span>Obavijesti uprave<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse " id="poruke1">
							@foreach(DB::table('notices')->take(5)->get() as $notice)
								<a href="{{ route('admin.notices.show', $notice->id ) }}">{{ $notice->subject }}</a>
							@endforeach
						</div>
					@endif
					
					@if(Sentinel::inRole('uprava'))
					<button class="poruke" data-toggle="collapse" data-target="#poruke3"><span>Prijedlozi upravi<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse " id="poruke3">
							@foreach(DB::table('posts')->where('to_employee_id','877282')->take(5)->get() as $prijedlozi)
								<a href="{{ route('admin.posts.show', $prijedlozi->id ) }}">{{ $prijedlozi->title }}</a>
							@endforeach
						</div>
					@endif
				@endif
				<!-- <a href="javascript:void(0);" class="icon" onclick="myFunction()">
					<i class="fa fa-bars"></i>
				  </a>-->
			</nav>
			<article class="col-xs-12 col-sm-10 col-md-10 col-lg-10" style="text-align:center;">
					@include('notifications')
					@yield('content')
			</article>
		
		</section>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('js/restfulizer.js') }}"></script>

		<!-- DataTables -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
  
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/datatables.min.js"></script>
		
		<script src="{{ asset('js/datatable.js') }}"></script>
		<script src="{{ asset('js/collaps.js') }}"></script>
		<script>
		function myFunction() {
			var x = document.getElementById("myTopnav");
			if (x.className === "topnav") {
				x.className += " responsive";
			} else {
				x.className = "topnav";
			}
		}
		</script>
				
		<!-- jQuery Timepicker --> 
		<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
		
		@stack('script')
    </body>
</html>
