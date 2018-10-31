@extends('layouts.admin')

@section('title', 'Nova poruka')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Nova poruka</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.posts.store') }}">
                <fieldset>
                    <div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
						<select class="form-control" name="to_employee_id" id="sel1" value="{{ old('to_employee_id') }}">
							<option selected="selected" value="">Prima...</option>
							<option name="uprava" value="uprava">Uprava</option>
							<option name="pravni" value="pravni">Pravna služba</option>
							<option name="it" value="it">IT služba</option>
							<option name="racunovodstvo" value="racunovodstvo">Računovodstvo</option>
						
						</select>
					</div>
					<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
                        <input class="form-control" name="title" list="post">
							<datalist id="post">
								<option value="Poruka zaposleniku">
								<option value="Prijedlog upravi">
								<option value="Poruka svima">
							</datalist>
						<!--<input class="form-control" placeholder="Post title" name="title" type="text" value="{{ old('title') }}" />-->
                        {!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
                       <textarea class="form-control" name="content" id="post-content"></textarea>
						
                        {!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
                    </div>

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Pošalji" id="stil1">
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
