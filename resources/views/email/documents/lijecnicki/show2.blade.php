<!DOCTYPE html>
<html lang="hr">
<head>
	<meta charset="utf-8">
	  <title>Uputnica</title>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
	  <link rel="stylesheet" href="{{ URL::asset('css/uputnica.css') }}"
</head>
	
<body>

	<header>
		<div class="headL">
			<p>DUPLICO d.o.o.</p>
			<p>Svetonedeljska 18, Kalinovica, Rakov Potok</p>
		</div>
		<div class="headR">
			<h4>Obrazac RA-1</h4>
			<p>Broj: ________________________________</p>
			<p>Datum: _______________________________</p>
			<p>OIB: <span class="oib">41025754642</span></p>
		</div>
		<h1>UPUTNICA</h1>
		<h3>za utvrđivanje zdravstvene sposobnosti radnika</h3>

	</header>		
	
	<section>
		<ul>
			<li>Ime i prezime: </li>
			<li class="box">{{ $employee->first_name . ' ' . $employee->last_name}}</li>
			<li>, </li>
			<li class="box">{{ $employee->ime_oca  . ' - ' . $employee->ime_majke }}</li>
		</ul>

		<p class="uvlaka">(ime oca – majke)</p>
		<ul>
			<li>Datum i mjesto rođenja: </li>
			<li class="box">{{ date('d.m.Y', strtotime($employee->datum_rodjenja) 
			). ' ' . $employee->mjesto_rodjenja}}</li>
			<li>, OIB</li>
			<li class="box2">{{ $employee->oib }}</li>
		</ul>
		<ul>
			<li>Zanimanje:</li>
			<li class="box">{{ $employee->zvanje }}</li>
			<li>, Školska sprema:</li>
			<li class="box2">{{ $employee->sprema  }}</li>
		</ul>
		<ul>
			<li>Poslovi za koje se utvrđuje zdravstvena sposobnost:</li>
			<li class="box3">{{ $employee->naziv  }}</li>
		</ul>
		<p>1) Poslovi  su prema članku ____3.____ točka __{{ $employee->tocke  }} .__ Pravilnika o poslovima s posebnim uvjetima rada.</p>
		<p>2) Poslovi prema drugim zakonima, propisima ili kolektivnom ugovoru: __Pravilnik o sigurnosti i zaštiti zdravlja pri_</p>
		<p>______radu s računalom (NN 69/05)_________________________ se utvrđuje zdravstvena sposobnost radnika.
		</p>
		<p>3) Poslovi su prema propisima o mirovinskom osiguranju utvrđeni kao poslovi na kojima se staž osiguranja</p>
		<p>računa s povećanim trajanjem.</p>
		<p>Ukupni radni staž: _____________ Radni staž na poslovima za koje se utvrđuje zdravstvena sposobnost:_____________</p>
		<p>Zdravstveni pregled: <span><i class="far fa-check-square"></i></span>prethodni  <span><i class="far fa-square"></i></span>periodički<span><i class="far fa-square"></i></span>izvanredni</p>
		<p>Posljednji zdravstveni pregled je učinjen ________________ prema članku _________________ točki ________________</p>
		<p>Pravilnika o poslovima s posebnim uvjetima rada, ili _____________________________________________________</p>
		<p>		______________________________________________________________________________________________</p>
		<p>(navesti zakon, propis ili kolektivni ugovor iz članka 2. stavka 1. podstavka 2. ili 3. Pravilnika)</p>
		<p>s ocjenom zdravstvene sposobnosti _____________________________________________________________________</p>
		<ul>
			<li>Kratak opis poslova:</li>
			<li class="box4">{{ $employee->naziv }}</li>

		</ul>
		<p>	_____________________________________________________________________________________________</p>
		<p>Strojevi, alati, aparati<sup>1</sup>: ___________________________________________________________________________</p>
		<p>Predmet rada<sup>2</sup>:  _________________________________________________________________________________
		</p>
	</section>
	<section class="rad">
		<div>
			<ul>
				<li class="prviR">Mjesto rada:</li>
				<li><span><i class="far fa-check-square"></i></span>u zatvorenom  </li>
				<li><span><i class="far fa-check-square"></i></span>na otvorenom </li>
				<li><span><i class="far fa-square"></i></span>na visini</li>
				<li><span><i class="far fa-square"></i></span>u jami</li>
				<li><span><i class="far fa-square"></i></span>u vodi</li>
				<li><span><i class="far fa-square"></i></span>pod vodom</li>
				<li><span><i class="far fa-square"></i></span>u mokrom</li>
			</ul>
		</div>
		<div>
			<ul>
				<li class="prviR">Organizacija:</li>
				<li><span><i class="far fa-square"></i></span>u smjenama</li>
				<li><span><i class="far fa-square"></i></span>noćni rad</li>
				<li><span><i class="far fa-check-square"></i></span>terenski rad</li>
				<li><span><i class="far fa-check-square"></i></span>radi sam</li>
				<li><span><i class="far fa-check-square"></i></span>radi s grupom</li>
				<li><span><i class="far fa-check-square"></i></span>radi sa strankama</li>
			</ul>
		</div>
		<div>
			<ul>
				<li class="prviR"></li>
				<li><span><i class="far fa-square"></i></span>radi na traci</li>
				<li><span><i class="far fa-square"></i></span>brzi tempo rada</li>
				<li><span><i class="far fa-square"></i></span>ritam određen</li>
				<li><span><i class="far fa-square"></i></span>monotonija.</li>
			</ul> 
		</div>
		
		<div>
			<ul>
				<li class="prviR">Položaj tijela i </li>
				<li><span><i class="far fa-check-square"></i></span>rad stojeći</li>
				<li><span><i class="far fa-square"></i></span>učestalo sagibanje</li>
				<li><span><i class="far fa-square"></i></span>podvlačenje</li>
				<li><span><i class="far fa-check-square"></i></span>rad sjedeći</li>
				<li><span><i class="far fa-square"></i></span>zakretanje trupa</li>
				<li><span><i class="far fa-square"></i></span>balansiranje</li>
			</ul>
		</div>
		<div>
			<ul>
				<li class="prviR">aktivnosti<sup>3</sup>:</li>
				<li><span><i class="far fa-check-square"></i></span>u pokretu</li>
				<li><span><i class="far fa-square"></i></span>klečanje</li>
				<li><span><i class="far fa-square"></i></span>uspinjanje ljestvama</li>
				<li><span><i class="far fa-square"></i></span>kombinirano</li>
				<li><span><i class="far fa-square"></i></span>čučanje</li>
				<li><span><i class="far fa-square"></i></span>uspinjanje stepenicama</li>
			</ul>
		</div>
		<div >
			<ul>
				<li class="prviR"></li>
				<li><span><i class="far fa-square"></i></span>diz.tereta __________ kg</li>
				<li><span><i class="far fa-square"></i></span>prenoš. tereta  __________ kg</li>
				<li><span><i class="far fa-square"></i></span>guranje tereta __________ kg</li>
			</ul>
		</div>
		<div >
			<ul>
				<li class="prviR">U poslu je</li>
				<li><span><i class="far fa-square"></i></span>vid na daljinu</li>
				<li><span><i class="far fa-check-square"></i></span>vid na blizinu</li>
				<li><span><i class="far fa-check-square"></i></span>raspoznavanje boja</li>
				<li><span><i class="far fa-square"></i></span>dobar sluh</li>
				<li><span><i class="far fa-square"></i></span>jasan govor</li>
			</ul>
		</div>
		<div>
			<ul>
				<li class="prviR">Uvjeti rada:</li>
				<li><span><i class="far fa-square"></i></span>visoka temperatura</li>
				<li><span><i class="far fa-square"></i></span>visoka vlažnost</li>
				<li><span><i class="far fa-square"></i></span>niska temperatura</li>
				<li><span><i class="far fa-square"></i></span>buka</li>
				<li><span><i class="far fa-square"></i></span>vibracije stroja ili alata</li>
			</ul>
		</div>
		<div >
			<ul>
				<li class="prviR"></li>
				<li><span><i class="far fa-square"></i></span>vibracije poda</li>
				<li><span><i class="far fa-square"></i></span>povišeni atmosferski tlak</li>
				<li><span><i class="far fa-square"></i></span>povećana izloženost ozljedama</li>
			</ul>
		</div>
		<div >
			<ul>
				<li class="prviR"></li>
				<li><span><i class="far fa-square"></i></span>ionizirajuća zračenja</li>
				<li><span><i class="far fa-square"></i></span>neionizirajuča zračenja tlak</li>
				<li><span><i class="far fa-square"></i></span>prašina.</li>
			</ul>
		</div>
		<div>
			<ul>
				<li class="prviR">Kemijske tvari:</li>
				<li class="box4">/</li>
			</ul>
			<ul>
				<li class="prviR">Biološke štetnosti:</li>
				<li class="box4">/</li>
			</ul>
		</div>
		<br/>
		<br/>
		<div class="headR">
		<p>M.P.  ________________________________<p>
		<p>(potpis odgovorne osobe)</p>
		</div>
		<div class="napomene">
		<sup>1</sup> upisuju se strojevi, alati i aparati kojima radnik rukuje ili poslužuje
		<br>
		<sup>2</sup> upisuju se radne tvari s kojima radnik rukuje ili dolazi u dodir
		<br>
		<sup>3</sup> zaokružuje se odgovarajući položaj tijela i aktivnosti koje se svakodnevno javljaju
		<br>
		<sup>4</sup> zaokružuje se funkcija bez koje se predviđeni posao ne može obaviti	   
		</div>
	</section>

	
	
</body>
</html>