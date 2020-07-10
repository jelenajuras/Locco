<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Visitor</title>
		<!-- Bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

		<link rel="stylesheet" href="{{ URL::asset('css/admin.css') }}" type="text/css" >
		<link rel="stylesheet" href="{{ URL::asset('css/dashboard.css') }}"/>

		<script src="{{ URL::asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
	</head>
	<body>
		<header>
			<h1><img src="{{ asset('img/Logo_Duplico.png') }}" /><span>portal za zaposlenike</span></h1>
			<ul class="">
				@if(Sentinel::check())
					<li><a href="{{ route('auth.logout') }}">Odjava</a></li>
				@else
				<!--	<li><a href="{{ route('auth.login.form') }}">Login</a></li>-->
				@endif
			</ul>
		</header>
		<section class="language">
			<div class="lang_choose">
				<label></label><img class="img_flag flag_hr" src="{{ asset('img/flag/hr-flag.png') }}" /><img class="img_flag flag_de" src="{{ asset('img/flag/de-flag.png') }}" /><img class="img_flag flag_en" src="{{ asset('img/flag/en-flag.png') }}" />
			</div>
		</section>
		<main class="visitors">
			@include('notifications')
			<section class="hr col-md-12 col-lg-9" >			
				<h1>UPUTE ZA SIGURNOST POSJETITELJA</h1>
				<h5>Poštovani posjetitelju, <br><br>
					dobrodošli u tvrtku Duplico! Jedna od temeljnih vrijednosti i prioriteta naše tvrtke je briga o zdravlju i zaštiti osoba na radu i posjetitelja, kroz sustave upravljanja kvalitetom, okolišem, zdravljem i sigurnosti na radu prema međunarodnim normama. Cilj naše tvrtke je osigurati maksimalnu sigurnost svim osobama prisutnima na lokaciji tvrtke, stoga Vas molimo da u nastavku upišete tražene podatke u svrhu evidencije Vašeg ulaska i boravka u tvrtki te da pažljivo pročitate Upute za sigurnost posjetitelja i da ih se pridržavate tijekom posjete.</h5>
				<form accept-charset="UTF-8" role="form" class="" method="post" action="{{ route('admin.visitors.store') }}">
					<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="Ime" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
						{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="Prezime" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
						{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
						{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="Tvrtka" name="company" type="text" maxlength="50" value="{{ old('company') }}">
						{!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input class="form-control" name="lang" type="hidden" value="hr">
					<div class="form-group col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12 smjernice">
						@include('admin.visitors.smjernice') 					
						<div class="form-group {{ ($errors->has('accept')) ? 'has-error' : '' }} ">
							<label>
								<input name="accept" type="checkbox" value="1" {{ old('activate') == 'true' ? 'checked' : ''}} > <b>Potvrđujem da sam upoznat sa uvjetima i smjernicama zaštite na radu tvrtke Duplico. </b>
							</label>
							{!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
						</div>
					</div>
					
					{{ csrf_field() }}
					<input class="btn-submit btn_submit_reg" type="submit" value="Potvrda"> 
				</form>
			</section>
			<section class="en col-md-12 col-lg-9" >
					<h1>Instructions for visitors</h1>
					<h5>Dear visitor!<br>
						For the record of your entry and stay at Duplico d.o.o. enter the information required below and be sure to read the instructions for visitors.</h5>
					<form accept-charset="UTF-8" role="form" class="" method="post" action="{{ route('admin.visitors.store') }}">
						<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
							<input class="form-control" placeholder="First name" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
							{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
							<input class="form-control" placeholder="Last name" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
							{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
							<input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
							{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
							<input class="form-control" placeholder="Company" name="company" type="text" maxlength="50" value="{{ old('company') }}">
							{!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<input class="form-control" name="lang" type="hidden" value="en">
						<div class="form-group col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12 smjernice">
							@include('admin.visitors.smjernice_en')
							<div class="form-group {{ ($errors->has('accept')) ? 'has-error' : '' }} ">
								<label>
									<input name="accept" type="checkbox" value="1" {{ old('activate') == 'true' ? 'checked' : ''}} > <b>I confirm that I am familiar with the Duplico occupational safety conditions and guidelines.</b>
								</label>
								{!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
							</div>
						</div>						
						{{ csrf_field() }}
						<input class="btn-submit btn_submit_reg" type="submit" value="Confirmation"> 
					</form>
			</section>
			<section class="de col-md-12 col-lg-9" >
				<h1>Anleitung für Besucher</h1>
				<h5>Lieber Besucher!<br>
					Bitte, um Ihren Eintrag zu erfassen und bei Duplico d.o.o. zu bleiben. Geben Sie die unten erforderlichen Informationen ein und lesen Sie die Anweisungen für Besucher.</h5>
				<form accept-charset="UTF-8" role="form" class="" method="post" action="{{ route('admin.visitors.store') }}">
					<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="Name" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
						{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="Nachname" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
						{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
						{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }} col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12">
						<input class="form-control" placeholder="Firma" name="company" type="text" maxlength="50" value="{{ old('company') }}">
						{!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input class="form-control" name="lang" type="hidden" value="en">
					<div class="form-group col-lg-4 col-lg-6 col-md-6 col-sm-10 col-xs-12 smjernice">
						@include('admin.visitors.smjernice_de')
						<div class="form-group {{ ($errors->has('accept')) ? 'has-error' : '' }} ">
							<label>
								<input name="accept" type="checkbox" value="1" {{ old('activate') == 'true' ? 'checked' : ''}} > <b>Ich bestätige, dass ich mit den Duplico-Arbeitsschutzbestimmungen und -richtlinien vertraut bin.</b>
							</label>
							{!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
						</div>
					</div>				
					{{ csrf_field() }}
					<input class="btn-submit btn_submit_reg" type="submit" value="Bestätigung"> 
				</form>
			</section>
		</main>
		<footer>

		</footer>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
		<script src="{{ asset('js/restfulizer.js') }}"></script>

		<script>
			$('.img_flag.flag_de').click(function(){
				$('.visitors > section').hide();
				$('.visitors > section.de').show();
			});
			$('.img_flag.flag_en').click(function(){
				$('.visitors > section').hide();
				$('.visitors > section.en').show();
			});
			$('.img_flag.flag_hr').click(function(){
				$('.visitors > section').hide();
				$('.visitors > section.hr').show();
			});
		</script>
	</body>
</html>