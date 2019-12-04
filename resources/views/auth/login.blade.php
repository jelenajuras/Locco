@extends('layouts.index')

@section('title', 'Login')

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4" >
        <div class="panel panel-default">
            <div class="panel-heading" id="stil1">
                <h3 class="panel-title">Prijava</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.login.attempt') }}" id="form">
					<fieldset>
						<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}" id="email">
							{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="Lozinka" name="password" type="password" value="">
							{!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="checkbox">
							<label>
								<input name="remember" type="checkbox" value="true" {{ old('remember') == 'true' ? 'checked' : ''}}> Zapamti me
							</label>
						</div>
						{{ csrf_field() }}
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="Prijavi me" id="stil1">
						<p style="margin-top:5px; margin-bottom:0"><a href="{{ route('auth.password.request.form') }}" type="submit">Zaboravio si lozinku?</a></p>
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
