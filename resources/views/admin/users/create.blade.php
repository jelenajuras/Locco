@extends('layouts.admin')

@section('title', 'Create New User')

@section('content')
<div class="page-header">
  <h2>Upiši novog korisnika</h2>
</div> 
<div class="" >
	 <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="panel panel-default">
             <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('users.store') }}">
                <fieldset>
                    <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Ime" name="first_name" type="text" value="{{ old('first_name') }}" autofocus/>
                        {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Prezime" name="last_name" type="text" value="{{ old('last_name') }}" />
                        {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">
                        {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <h5>Roles</h5>
                    @foreach ($roles as $role)
                        @if($role->name != 'SuperAdmin')
							<div class="checkbox">
								<label>
									<input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}">
									{{ $role->name }}
								</label>
							</div>
						@endif
                    @endforeach
                    <hr />
                    <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Lozinka" name="password" type="password" value="">
                        {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Potvrdi lozinku" name="password_confirmation" type="password" />
                        {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="checkbox">
                       <!-- <label>
                            <input name="activate" type="checkbox" value="true" {{ old('activate') == 'true' ? 'checked' : ''}}> Activate
                        </label>-->
                    </div>
                    {{ csrf_field() }}
                    <input class="btn btn-lg btn-default btn-block" type="submit" value="Upiši" id="border1"> 
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
