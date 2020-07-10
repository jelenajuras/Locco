@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="page-header">
	@if(Sentinel::inRole('basic'))
		<h2>Ispravi lozinku</h2>
	@else
		<h2>Ispravi podatke korisnika</h2>
	@endif
</div> 
<div class="">
	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="panel panel-default">
            
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('users.update', $user->id) }}">
                <fieldset>
                    <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Ime" name="first_name" value="{{ $user->first_name }}"  {!! Sentinel::inRole('basic') ? 'type="hidden"' : 'type="text"' !!} />
                        {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Prezime" name="last_name"value="{{ $user->last_name }}" {!! Sentinel::inRole('basic') ? 'type="hidden"' : 'type="text"' !!} />
                        {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="E-mail" name="email" value="{{ $user->email }}" {!! Sentinel::inRole('basic') ? 'type="hidden"' : 'type="text"' !!}>
                        {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="{!! Sentinel::inRole('basic') ? 'hidden' : '' !!}" >
						<h5>Roles</h5>
						@foreach ($roles as $role)
							@if($role->name != 'SuperAdmin')
							<div class="checkbox">
								<label>
									<input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}" {{ $user->inRole($role) ? 'checked' : '' }}>
									{{ $role->name }}
								</label>
							</div>
							@endif
						@endforeach
						 <hr />
					</div>
                   
                    <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Lozinka" name="password" type="password" value="">
                        {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Potvrdi lozinku" name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" />
                        {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi" id="stil1">
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
