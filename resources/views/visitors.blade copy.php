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
@php	
	$card_id = substr($_SERVER['REQUEST_URI'],10);
@endphp
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
			<section class="hr col-md-12 col-lg-9 col-xl-6" >			
				<h1>Upute za sigurnost posjetitelja</h1>
				<h5>Poštovani posjetitelju, <br><br>
					dobrodošli u tvrtku Duplico! Jedna od temeljnih vrijednosti i prioriteta naše tvrtke je briga o zdravlju i zaštiti osoba na radu i posjetitelja, kroz sustave upravljanja kvalitetom, okolišem, zdravljem i sigurnosti na radu prema međunarodnim normama. Cilj naše tvrtke je osigurati maksimalnu sigurnost svim osobama prisutnima na lokaciji tvrtke, stoga Vas molimo da u nastavku upišete tražene podatke u svrhu evidencije Vašeg ulaska i boravka u tvrtki te da pažljivo pročitate Upute za sigurnost posjetitelja i da ih se pridržavate tijekom posjete.</h5>
				<form accept-charset="UTF-8" role="form" class="visitor_form" method="post" action="{{ route('admin.visitors.store') }}">
					<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }} ">
						<input class="form-control" placeholder="Ime" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
						{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Prezime" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
						{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
						{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Tvrtka" name="company" type="text" maxlength="50" value="{{ old('company') }}">
						{!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input class="form-control" name="lang" type="hidden" value="hr">
					<div class="form-group smjernice">
						@include('admin.visitors.smjernice') 					
						<div class="{{ ($errors->has('accept')) ? 'has-error' : '' }} ">
							<label>
								<input name="accept" type="checkbox" value="1" {{ old('accept') == 'checked' ? 'checked' : ''}}  > <b>Potvrđujem da sam upoznat sa uvjetima i smjernicama zaštite na radu tvrtke Duplico.</b>
							</label>
							{!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="{{ ($errors->has('confirm')) ? 'has-error' : '' }} ">
							<label>
								<input name="confirm" type="checkbox" value="1" {{ old('confirm') == 'true' ? 'checked' : ''}} > <b>Potvrđujem da sam preuzeo/la i da sam upoznat s načinom korištenja ključa za ulazak u prostorije tvrtke </b>
							</label>
							{!! ($errors->has('confirm') ? $errors->first('confirm', '<p class="text-danger">:message</p>') : '') !!}
						</div>
					</div>
					<input class="form-control" name="card_id" type="hidden" maxlength="20" value="{{ $card_id }}">
					{{ csrf_field() }}
					<input class="btn-submit btn_submit_reg" type="submit" value="Potvrda"> 
				</form>
				<p>* Vaše osobne podatke obrađujemo sukladno članku 6. Opće uredbe o zaštiti podataka (GDPR), a u svrhu poštivanja pravnih obveza Duplico d.o.o. i zaštite Vaših ključnih interesa.</p>
				@if(! isset($_COOKIE['cookie_confirme'])  )
					<footer class="cookie">
						<span>Kolačiće koristimo kako bismo Vam pružili najbolje iskustvo na našoj web stranici. Ako nastavite koristiti ovu stranicu pretpostaviti ćemo da ste zadovoljni njome. </span>
						<button class="close_cookie">OK</button>
						<a class="cookie_info" href="http://www.duplico.hr/pravila-o-zastiti-i-privatnosti/" >Pročitaj više</a>
					</footer>
				@endif
			</section>
			<section class="en col-md-12 col-lg-9 col-xl-6" >	
				<h1>Visitors safety instructions</h1>
				<h5>Dear visitor,<br><br>
					welcome to Duplico! One of the core values and priorities of our company is the care for the health and safety of workers and visitors, through quality, environmental, health and safety management systems in accordance with international standards. The goal of our company is to provide maximum security to all those present at the company location, so please enter the required information below for the purpose of recording your entry and stay at the company and to read carefully the Visitor Safety Instructions and to abide by them during your visit.</h5>
				<form accept-charset="UTF-8" role="form" class="visitor_form" method="post" action="{{ route('admin.visitors.store') }}">
					<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="First name" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
						{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Last name" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
						{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
						{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Company" name="company" type="text" maxlength="50" value="{{ old('company') }}">
						{!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input class="form-control" name="lang" type="hidden" value="en">
					<div class="form-group smjernice">
						@include('admin.visitors.smjernice_en')
						<div class="{{ ($errors->has('accept')) ? 'has-error' : '' }} ">
							<label>
								<input name="accept" type="checkbox" value="1" {{ old('accept') == 'true' ? 'checked' : ''}} > <b>I hereby confirm that I have read, understood and accepted the Visitors Safety Instructions</b>
							</label>
							{!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="{{ ($errors->has('confirm')) ? 'has-error' : '' }} ">
							<label>
								<input name="confirm" type="checkbox" value="1" {{ old('confirm') == 'true' ? 'checked' : ''}} > <b>I hereby confirm that I have taken over and that I am familiar with how to use the key to enter the  
									company premises
								</b>
							</label>
							{!! ($errors->has('confirm') ? $errors->first('confirm', '<p class="text-danger">:message</p>') : '') !!}
						</div>
					</div>			
					<input class="form-control" name="card_id" type="hidden" maxlength="20" value="{{ $card_id }}">			
					{{ csrf_field() }}
					<input class="btn-submit btn_submit_reg" type="submit" value="Confirmation"> 
				</form>
				<p>* We process your personal information in accordance with Article 6 of the General Data Protection Regulation (GDPR), and in order to comply with the legal obligations of Duplico d.o.o. and protecting your key interests.</p>
				@if(! isset($_COOKIE['cookie_confirme'])  )
					<footer class="cookie">
						<span>We use cookies to ensure thet we give you the best experience on our website. If you continue to use this site, we will assume that you are happy with it.</span>
						<button class="close_cookie">OK</button>
						<a class="cookie_info" href="http://www.duplico.hr/en/privacy-protection-policy/" >Read more</a>
					</footer>
				@endif
			</section>
			<section class="de col-md-12 col-lg-9 col-xl-6" >	
				<h1>Sicherheitshinweise für besucher</h1>
				<h5>Sehr gehrten Besucher,<br><br>
					Wilkommen bei Duplico! Einer der zentralen Werte und Prioritäten unseres Unternehmens ist die Gewährleistung der Gesundheit und Sicherheit von Arbeitnehmern und Besuchern durch Qualitäts-, Umwelt-, Gesundheits- und Sicherheitsmanagementsysteme gemäß internationalen Standards.
					Das Ziel unseres Unternehmens ist es, allen Anwesenden am Unternehmensstandort ein Höchstmaß an Sicherheit zu bieten. Geben Sie daher die erforderlichen Informationen ein, um Ihren Aufenthalt im Unternehmen zu protokollieren und die Besuchersicherheitsrichtlinien sorgfältig zu lesen und bei Ihrem Besuch einzuhalten.
					</h5>
				<form accept-charset="UTF-8" role="form" class="visitor_form" method="post" action="{{ route('admin.visitors.store') }}">
					<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Name" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
						{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Nachname" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
						{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
						{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Firma" name="company" type="text" maxlength="50" value="{{ old('company') }}">
						{!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<input class="form-control" name="lang" type="hidden" value="en">
					<div class="form-group smjernice">
						@include('admin.visitors.smjernice_de')
						<div class="{{ ($errors->has('accept')) ? 'has-error' : '' }} ">
							<label>
								<input name="accept" type="checkbox" value="1" {{ old('accept') == 'true' ? 'checked' : ''}} > <b>Hiermit bestätige ich, dass ich die Hinweise zur Besuchersicherheit gelesen, verstanden und akzeptiert habe!</b>
							</label>
							{!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="{{ ($errors->has('confirm')) ? 'has-error' : '' }} ">
							<label>
								<input name="confirm" type="checkbox" value="1" {{ old('confirm') == 'true' ? 'checked' : ''}} > <b>I hereby confirm that I have taken over and that I am familiar with how to use the key to enter the  
									company premises
								</b>
							</label>
							{!! ($errors->has('confirm') ? $errors->first('confirm', '<p class="text-danger">:message</p>') : '') !!}
						</div>
					</div>				
					<input class="form-control" name="card_id" type="hidden" maxlength="20" value="{{ $card_id }}">	
					{{ csrf_field() }}
					<input class="btn-submit btn_submit_reg" type="submit" value="Bestätigung"> 
				</form>
				<p>* Wir verarbeiten Ihre personenbezogenen Daten gemäß Artikel 6 der Allgemeinen Datenschutzverordnung (DSGVO) und um die gesetzlichen Verpflichtungen von Duplico d.o.o. zu gewährleisten  und zum Schutz Ihrer Hauptinteressen</p>
				@if( ! isset($_COOKIE['cookie_confirme'])  )
					<footer class="cookie">
						<span>Wir verwenden Cookies, um Ihnen die bestmögliche Erfahrung auf unserer Website zu bieten. Wenn Sie diese Website weiterhin verwenden, gehen wir davon aus, dass Sie damit zufrieden sind.</span>
						<button class="close_cookie">OK</button>
						<a class="cookie_info" href="http://www.duplico.hr/de/datenschutzrichtlinie/" >Lesen Sie mehr</a>
					</footer>
				@endif
			</section>			
		</main>		

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
		<script src="{{ asset('js/restfulizer.js') }}"></script>

		<script>
			$('.img_flag.flag_de').click(function(){

				url = window.location.origin + '/de' + window.location.pathname;
				
				
				console.log(url);
				
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
			$('.close_cookie').click(function(){
				$('.cookie').remove();
				document.cookie = 'cookie_confirme=Duplico_'+Math.random().toString(36).substring(7);
				
			});

			$('.smjernice').click(function(){

			});
		</script>
	</body>
</html>