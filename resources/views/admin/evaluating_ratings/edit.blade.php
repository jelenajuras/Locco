@extends('layouts.admin')

@section('title', 'Ispravak ocjene')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">Ispravak ocjene</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.evaluating_ratings.update', $evaluatingRating->id) }}">
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Opis</label>
						<input name="naziv" type="text" class="form-control" value="{{ $evaluatingRating->naziv }}"/>
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('rating'))  ? 'has-error' : '' }}">
                        <label>Ocjena</label>
						<input list="rating" name="rating" class="form-control" value="{{ $evaluatingRating->rating }}">
						<datalist id="rating" >
							<option value="0" {!! $evaluatingRating->rating === '0' ? 'selected' : '' !!} >
							<option value="1" {!! $evaluatingRating->rating === '1' ? 'selected' : '' !!} >
							<option value="2" {!! $evaluatingRating->rating === '2' ? 'selected' : '' !!} >
							<option value="3" {!! $evaluatingRating->rating === '3' ? 'selected' : '' !!} >
							<option value="4" {!! $evaluatingRating->rating === '4' ? 'selected' : '' !!} >
						</datalist> 
						{!! ($errors->has('rating') ? $errors->first('rating', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop