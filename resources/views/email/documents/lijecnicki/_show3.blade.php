<!DOCTYPE html>
<html lang="hr">
<head>
	<meta charset="utf-8">
	  <title>Uputnica</title>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
</head>
	
<style>
@page
{ 
    size: auto; 
    margin:10mm 10mm 10mm 10mm;  
} 
#padding {
    padding-left: 1cm;
}
#padding2 {
    padding-left: 0.5cm;
}
#padding3 {
    padding-left: 11cm;
	font-size: 10px;
}
#padding4 {
    padding-top: 0.2cm;
}
body { 
	font-family: DejaVu Sans, sans-serif;
	font-size: 13px;
}
#p1 { 
	font-size: 16px;
}
#box {
	width: 250px;
    height: 90px;
	border: 1px solid black;
}
#right {
	text-align: right;
}
#center {
	text-align: center;
}
#napomene{
	font-size: 10px;
}

</style>

<body>
<div class="container">
	<div class="col-lg-8" >
		<div class="row">
			<div class="col-lg-8" id="right">
				<b>Obrazac RA-1</b>
			</div>
			<div class="col-lg-8" id="left">
				DUPLICO d.o.o.<br>
				Svetonedeljska 18, Kalinovica, Rakov Potok
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8" id="right">
				Broj: ______________________
			</div>
			<div class="col-lg-8" id="right">
				Datum: ______________________
			</div>
			<div class="col-lg-8" id="right">
				OIB: _4_1_0_2_5_7_5_4_6_4_2_
			</div>
		</div>
	</div>		
	
	<div class="row">
		  <div class="col-lg-8"  id="center">
			<p id="p1"><b>UPUTNICA</b></p>
			<p >za utvrđivanje zdravstvene sposobnosti radnika</p>
		  </div>
	</div>
	<br/>
	<div class="row">
		<div>
			Ime i prezime:______________ <b>{{ $employee->first_name . ' ' . $employee->last_name}} </b> ______________, ______________<b>{{ $employee->ime_oca  . ' - ' . $employee->ime_majke }} </b>______________
		</div>
		<div id="padding3">
		(ime oca – majke)
		</div>
		<div>
		Datum i mjesto rođenja: _________ <b>{{ date('d.m.Y', strtotime($employee->datum_rodjenja))  . ' ' . $employee->mjesto_rodjenja}} </b> _________,  OIB _________<b>{{ $employee->oib }}</b>_______________
		</div>
		<div id="padding4">
		Zanimanje: _________<b>{{ $employee->zvanje  }}</b>_________,  Školska sprema: _________<b>{{ $employee->sprema  }}</b>_________
		</div>
		<div id="padding4">
		Poslovi za koje se utvrđuje zdravstvena sposobnost:_________<b>{{ $employee->naziv  }}</b>_________
		</div>
		<div id="padding4">
		1) Poslovi  su prema članku ____3.____ točka _________ Pravilnika o poslovima s posebnim uvjetima rada.
		</div>
		<div id="padding4">
		2) Poslovi prema drugim zakonima, propisima ili kolektivnom ugovoru: ____Pravilnik o sigurnosti i zaštiti zdravlja pri___
		</div>
		<div id="padding4">
		__radu s računalom (NN 69/05)_____________________________ se utvrđuje zdravstvena sposobnost radnika.
		</div>
		<div id="padding4">
		3) Poslovi su prema propisima o mirovinskom osiguranju utvrđeni kao poslovi na kojima se staž osiguranja
		</div>
		<div id="padding4">
		računa s povećanim trajanjem.
		</div>
		<div id="padding4">
		Ukupni radni staž: _________ Radni staž na poslovima za koje se utvrđuje zdravstvena sposobnost:__________
		</div>
		<div id="padding4">
		Zdravstveni pregled: <span class="glyphicon glyphicon-check" aria-hidden="true"></span>prethodni  <span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>periodički<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>izvanredni        
		</div>
		<div id="padding4">
		Posljednji zdravstveni pregled je učinjen ______________ prema članku ______________ točki _____________
		</div>
		<div id="padding4">
		Pravilnika o poslovima s posebnim uvjetima rada, ili ________________________________________________
		</div>
		<div id="padding4">
		__________________________________________________________________________________________
		</div>
		<div id="padding4">
		(navesti zakon, propis ili kolektivni ugovor iz članka 2. stavka 1. podstavka 2. ili 3. Pravilnika)
		</div>
		<div id="padding4">
		s ocjenom zdravstvene sposobnosti _____________________________________________________________
		</div>
		<div id="padding4">
		Kratak opis poslova:_________<b>{{ $employee->naziv  }}</b>_________
		</div>
		<div id="padding4">
		______________________________________________________________________________________
		</div>
		<div id="padding4">
		Strojevi, alati, aparati<sup>1</sup>: _________________________________________________________________________________
		</div>
		<div id="padding4">
		Predmet rada<sup>2</sup>:  _______________________________________________________________________________________
		</div>
		<div id="padding4">
		Mjesto rada:
			<span class="glyphicon glyphicon-check" aria-hidden="true"></span>u zatvorenom  
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>na otvorenom 
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>na visini
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>u jami
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>u vodi
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>pod vodom
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>u mokrom
		</div>
		<div id="padding4">
		Ogranizacija 
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>u smjenama    
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>noćni rad    
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>terenski rad
			<span class="glyphicon glyphicon-check" aria-hidden="true"></span>radi sam
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>radi s grupom
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>radi sa strankama
		</div>
		<div id="padding4">
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>radi na traci    
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>brzi tempo rada
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>ritam određen
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>monotonija.
		</div>
		<div id="padding4">
		Položaj tijela i aktivnosti<sup>3</sup>
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>rad stojeći    
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>učestalo sagibanje   
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>podvlačenje   
			<span class="glyphicon glyphicon-check" aria-hidden="true"></span>rad sjedeći
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>zakretanje trupa
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>balansiranje
		</div>
		<div id="padding4">
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>u pokretu 
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>klečanje  
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>uspinjanje ljestvama  
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>kombinirano  
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>čučanje  
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>uspinjanje stepenicama
		</div>
		<div id="padding4">
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>diz.tereta __________ kg 
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>prenoš. tereta  __________ kg 
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>guranje tereta __________ kg 
		</div>
		<div id="padding4">
		U poslu je 
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>vid na daljinu  
			<span class="glyphicon glyphicon-check" aria-hidden="true"></span>vid na blizinu
			<span class="glyphicon glyphicon-check" aria-hidden="true"></span>raspoznavanje boja
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>dobar sluh
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>jasan govor
		</div>
		<div id="padding4">
		Uvjeti rada:
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>visoka temperatura
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>visoka vlažnost
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>niska temperatura
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>buka
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>vibracije stroja ili alata 
		</div>
		<div id="padding4">
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>vibracije poda
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>povišeni atmosferski tlak
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>povećana izloženost ozljedama
		</div>
		<div id="padding4">
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>ionizirajuća zračenja
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>neionizirajuča zračenja tlak
			<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>prašina.
		</div>
		<div id="padding4">
		Kemijske tvari: ___/_____________________________________________________________________________
		</div>
		<div id="padding4">
		Biološke štetnosti: ___/_____________________________________________________________________________
		</div>
		<br/>
		<br/>
		<div id="right">
		M.P.  ________________________________ <br>(potpis odgovorne osobe)  
		</div>
		<div id="napomene">
		<sup>1</sup> upisuju se strojevi, alati i aparati kojima radnik rukuje ili poslužuje
		<br>
		<sup>2</sup> upisuju se radne tvari s kojima radnik rukuje ili dolazi u dodir
		<br>
		<sup>3</sup> zaokružuje se odgovarajući položaj tijela i aktivnosti koje se svakodnevno javljaju
		<br>
		<sup>4</sup> zaokružuje se funkcija bez koje se predviđeni posao ne može obaviti	   
		</div>
	</div>
	</div>
</div>
	
	
</body>
</html>