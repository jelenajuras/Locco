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
	</style>
	<body>
		<div class="content">
			<h3>Prijava greške</h3>

			<p><b>host:</b>  {!! $url  !!}</p>
			<p><b>request uri:</b>  {!! $request_uri  !!}</p>
			<p><b>exception:</b> {!! $request !!}</p>
			<p><b>user email:</b> {!! $user_mail  !!}</p>
			<p><b>Korisnik:</b> {{ $user }}</p>
		</div>
	</body>
</html>