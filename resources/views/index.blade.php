@extends('layouts.index')

@section('title', 'Duplico')
<link rel="stylesheet" href="{{ URL::asset('css/welcome.css') }}" type="text/css" >
@section('content')
 
<div class="flex-center position-ref full-height">
	@if (Route::has('login'))
		<div class="top-right links">
			@auth
				<a href="{{ url('/home') }}">Home</a>
			@else
				<a href="{{ route('auth.login.form') }}">Login</a>
				<a href="{{ route('auth.register.form') }}">Register</a>
			@endauth
		</div>
	@endif

	<div class="content">
		<div class="title m-b-md">
			<img src="{{ asset('img/Logo_Duplico.png') }}" /> portal za zaposlenike
		</div>

	</div>
</div>
@stop
