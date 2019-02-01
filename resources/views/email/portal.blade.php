<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
	</head>
	<style>
	body { 
		font-family: DejaVu Sans, sans-serif;
		font-size: 10px;
	}
	.content {
		width:auto;
		height: auto;
		margin:auto;
		text-align:left;
		font-size:14px;
	}
	.group {
		padding-top: 20px;
	}
	.group2 {
		padding-left: 20px;
	}
	</style>
	<body>
		<div class="content">
			<div class="group">
				<p>Dostavljamo pristupne podatke za PORTAL ZA ZAPOSLENIKE </p>
				<p>Poralu pristupate putem  <a href="{{ $link }}" >linka</a></p>
				<p>Pristupni podaci: </p>
				<div class="group2">
				<p>korisničko ime: {{ $email }}</p>
				<p>lozinka: {{ $lozinka}}</p>
				</div>
			</div>
			<div class="group">
				<p>Nakon prvog pristupa stranici obavezno promijenite lozinku.</p>
				<p>Svoje pristupne podatke nemojte odavati drugiom osobama.</p>
			</div>
			<div class="group">
				<p>Upute za korištenje možete naći na Portalu klikom na link "Dokumenti"</p>
				<p>Za sva pitanja javite se na email {{ $podrska }}</p>
			</div>
		</div>
	</body>
</html>