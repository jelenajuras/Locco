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
		
		<!-- style --> 
		<link rel="stylesheet" href="{{ URL::asset('css/admin.css') }}" type="text/css" >
		<link rel="stylesheet" href="{{ URL::asset('css/dashboard.css') }}"/>


		<!-- jQuery Timepicker --> 
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
		
		<!-- include libraries(jQuery, bootstrap) -->
		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">

		<!-- include summernote css/js -->
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 

		<!-- include summernote css/js -->
		<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
		<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	 	<!-- include pignose css -->
		<link rel="stylesheet" href="{{ URL::asset('node_modules/pg-calendar/dist/css/pignose.calendar.min.css') }}" />

		<script src="{{ URL::asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
	 
		@stack('stylesheet')
    </head>
    <body>
		<header>
			<h1><img src="{{ asset('img/Logo_Duplico.png') }}" /><span>portal za zaposlenike</span></h1>
			<ul class="">
				@if(Sentinel::check())
					<li><a href="{{ route('auth.logout') }}">Odjava</a></li>
				@else
					<li><a href="{{ route('auth.login.form') }}">Login</a></li>
					<li><a href="{{ route('auth.register.form') }}">Register</a></li>
				@endif
			</ul>
		</header>
		<section class="Body_section">
			<input type="hidden" id="rola" {!! Sentinel::inRole('basic') ? 'value="basic"' : '' !!} />
			<nav class="topnav col-xs-12 col-sm-3 col-md-3 col-lg-2" id="myTopnav">
				@if(Sentinel::check() && !Sentinel::inRole('otkaz'))
					<a href="{{ route('home') }}" class="naslov">Naslovnica</a>
					<a href="{{ route('users.edit', Sentinel::getUser('id')) }}">Ispravi lozinku</a></li>
					@if (! Sentinel::inRole('temporary') && ! Sentinel::inRole('erp_test'))
						<a class="" href="{{ route('admin.ads.index') }}">Predaj oglas</a>
					@endif
					@if(Sentinel::inRole('administrator') || Sentinel::inRole('uprava') || Sentinel::inRole('basic') || Sentinel::inRole('temporary') )
						<a class="" href="{{ route('admin.documents.index') }}">Dokumenti</a>
					@endif				
					@if(Sentinel::getUser()->email == 'marina.sindik@duplico.hr')
						<a class="" href="{{ route('admin.registrations.index') }}">Prijavljeni radnici</a>
					@endif
					@if(Sentinel::inRole('administrator') || Sentinel::inRole('uprava'))
						@if(Sentinel::inRole('superadmin'))
							<button class="collapsible poruke {{ !Sentinel::inRole('superadmin') ? 'isDisabled' : '' }}"><span>SUPER ADMIN<i class="fas fa-caret-down"></i></span></button>
							<div class="collapse">
								<a class="{{ !Sentinel::inRole('superadmin') ? 'isDisabled' : '' }}" href="{{ route('roles.index') }}">Uloge</a>
								<a class="{{ !Sentinel::inRole('superadmin') ? 'isDisabled' : '' }}" href="{{ route('admin.tables.index') }}">Tablice</a>
							</div>
						@endif
						<button class="collapsible poruke {{ !Sentinel::inRole('administrator') ? 'isDisabled' : '' }}"><span>Opći podaci<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse ">
							<a class="" href="{{ route('users.index') }}">Korisnici</a>
							<a class="" href="{{ route('admin.works.index') }}">Radna mjesta</a>
							<a class="" href="{{ route('admin.departments.index') }}">Odjeli</a>
							<a class="" href="{{ route('admin.terminations.index') }}">Otkazi</a>
							<a class="" href="{{ route('admin.equipments.index') }}" >Radna oprema</a>
							<a class="" href="{{ route('admin.trainings.index') }}">Osposobljavanja</a>
							<a class="" href="{{ route('admin.cars.index') }}">Vozila</a>	
						</div>
						<button class="collapsible poruke {{ !Sentinel::inRole('administrator') ? 'isDisabled' : '' }}"><span>Administracija<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse ">
							<a class="" href="{{ route('admin.job_interviews.index') }}">Razgovori za posao</a>
							<a class="" href="{{ route('admin.employees.index') }}">Kandidati za posao</a>
							<a class="" href="{{ route('admin.registrations.index') }}">Prijavljeni radnici</a>
							<a class="" href="{{ route('admin.temporary_employees.index') }}">Privremeni radnici</a>
							<a class="" href="{{ route('admin.employee_trainings.index') }}">Osposobljavanja radnika</a>
							<a class="" href="{{ route('admin.employee_departments.index') }}">Zaposenici po odjelima</a>
							<a class="" href="{{ route('admin.employee_equipments.index') }}">Zadužena oprema</a>
							<a class="" href="{{ route('admin.kids.index') }}">Djeca zaposlenika</a>
							<a class="" href="{{ route('admin.employee_terminations.index') }}">Odjavljeni radnici</a>
						</div>
						<button class="collapsible poruke"><span>Evidencija rada i izostanci<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse">
							<a class="" href="{{ route('admin.vacation_requests.index') }}">Zahtjevi za godišnji odmor</a>
							<a class="" href="{{ route('admin.afterHours.index') }}">Prekovremeni rad</a>
							<a class="" href="{{ route('admin.job_records.index') }}">Evidencija rada</a>
							<a class="" href="{{ route('admin.shedulers.index') }}">Raspored izostanaka</a>	
						</div>
						<button class="collapsible poruke"><span>Zadaci<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse">
							<a class="" href="{{ route('admin.tasks.index') }}">Zadaci</a>
						</div>

						<button class="collapsible poruke"><span>Projekti<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse">
							<a class="" href="{{ route('admin.customers.index') }}">Klijenti</a>
							<a class="" href="{{ route('admin.projects.index') }}">Projekti</a>
						</div>
						<button class="collapsible poruke"><span>Ostalo<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse ">
							<a class="" href="{{ route('admin.instructions.index') }}">Radne upute</a>
							<a class="" href="{{ route('admin.catalog_categories.index') }}">Katalog opreme</a>
							<a class="" href="{{ route('admin.benefits.index') }}">Pogodnosti za zaposlenike</a>
							<a class="" href="{{ route('admin.notices.index') }}">Obavijesti</a>
							<a class="" href="{{ route('admin.visitors.show',0) }}">Posjetitelji</a>
							<!--<a class="" href="{{ route('admin.showKalendar') }}">Kalendar sastanaka</a>-->
							
							@if(Sentinel::inRole('uprava'))
								<a class="" href="{{ route('admin.effective_hours.index') }}">ECH</a>
							@endif
						</div>
						<button class="collapsible poruke"><span>Ankete<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse">
							<a class="" href="{{ route('admin.questionnaires.index') }}">Ankete</a>
							<a class="}" href="{{ route('admin.evaluating_groups.index') }}">Kategorije</a>
							<a class="" href="{{ route('admin.evaluating_questions.index') }}">Podkategorije</a>
							<a class="" href="{{ route('admin.evaluating_ratings.index') }}">Ocjene</a>
							<a class="" href="{{ route('admin.evaluating_employees.index') }}">Zaposlenici</a>
							@if(Sentinel::inRole('uprava') || Sentinel::getUser()->last_name == 'Barberić' || Sentinel::getUser()->last_name == 'Juras') <!--  Vide uprava i Matija--> 
								<a class="" href="{{ route('admin.evaluations.index') }}">Rezultati</a>
							@endif
						</div>
						<button class="collapsible poruke"><span>Edukacija<i class="fas fa-caret-down"></i></span></button>
						<div class="collapse">
							<a class="" href="{{ route('admin.educations.index') }}">Edukacije</a>
							<a class="" href="{{ route('admin.education_themes.index') }}">Teme</a>
							<a class="" href="{{ route('admin.education_articles.index') }}">Članci</a>
							<a class="" href="{{ route('admin.presentations.index') }}">Prezentacije</a>
						</div>
					@endif
					<div class="noticesHome">
						<!--@if(DB::table('notices')->take(5)->get())
						<button class="poruke" data-toggle="collapse" data-target="#poruke1"><span>Obavijesti uprave<i class="fas fa-caret-down"></i></span></button>
							<div class="collapse " id="poruke1">
								@foreach(DB::table('notices')->orderBy('created_at','DESC')->take(5)->get() as $notice)
									<a href="{{ route('admin.notices.show', $notice->id ) }}">{{ $notice->subject }}</a>
								@endforeach
							</div>
						@endif-->
						@if(Sentinel::inRole('uprava'))
							@if(count(DB::table('posts')->where('to_employee_id','877282')->orderBy('created_at','DESC')->take(5)->get()))
								<button class="collapsible poruke"><span>Prijedlozi upravi<i class="fas fa-caret-down"></i></span></button>
								<div class="collapse">
									@foreach(DB::table('posts')->where('to_employee_id','877282')->take(5)->get() as $prijedlozi)
										<a href="{{ route('admin.posts.show', $prijedlozi->id ) }}">{{ $prijedlozi->title }}</a>
									@endforeach
								</div>
							@endif
						@endif
					</div>
				
				<a href="javascript:void(0);" class="icon" onclick="myFunction()">
				 <i class="fa fa-bars"></i>
				</a>
				@endif
			</nav>
			@if(Sentinel::check() && !Sentinel::inRole('otkaz'))
				<section class="col-xs-12 col-sm-9 col-md-9 col-lg-10 body_section">
						@include('notifications')
						@yield('content')
				</section>
			@endif
		
		</section>
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('js/restfulizer.js') }}"></script>
		
		<!-- include libraries(jQuery, bootstrap) -->
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 

		<!-- include summernote css/js -->
		<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
		
		<!-- DataTables -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.css"/>
 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.js"></script>
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
		<script>
			/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
	/*		var dropdown = document.getElementsByClassName("collapsible");
			var i;
			for (i = 0; i < dropdown.length; i++) {
			dropdown[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var dropdownContent = this.nextElementSibling;
			if (dropdownContent.style.display === "block") {
			  dropdownContent.style.display = "none";
			} else {
			  dropdownContent.style.display = "block";
			}
			});
			}*/
		</script>
		@stack('script')
    </body>
</html>
