@extends('layouts.admin')

@section('title', 'Naslovnica')

@section('content')
@if(Sentinel::check())
	<header>
		<h1>Dashboard</h1>
		<h2>Hey {{ $user->first_name }}</h2>
		<div class="salary">
			<p>{{ number_format($ech['brutto'],2,",",".") . ' kn' }}<i class="fas fa-info-circle"></i></p>
			<h3>Yearly salary</h3>
		</div>
		<div class="salary">
			<p>{{  number_format($ech['effective_cost'],2,",",".") . ' kn' }}<i class="fas fa-info-circle"></i></p>
			<h3>Hourly rate</h3>
		</div>
	</header>
@else
	<div class="jumbotron">
		<h1>Welcome, Guest!</h1>
		<p>You must login to continue.</p>
		<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">Log In</a></p>
	</div>
@endif

@stop
