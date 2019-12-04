@extends('layouts.admin')

@section('title', 'Nova kategorija')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Upis nove kategorije</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.questionnaires.store') }}">
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Naziv ankete</label>
						<input name="naziv" type="text" class="form-control" value="{{ old('naziv') }}">
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('opis'))  ? 'has-error' : '' }}">
                        <label>Opis</label>
						<textarea name="opis" type="text" class="form-control" value="{{ old('opis') }}"></textarea>
						{!! ($errors->has('opis') ? $errors->first('opis', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
                        <label>Status</label>
						<input type="radio" class="" name="status" value="neaktivna" checked />NEAKTIVNA 
						<input type="radio" class="" name="status" value="aktivna" />AKTIVNA
						{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop