@extends('layouts.admin')

@section('title', 'Ispravak kategorije')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Ispravak kategorije</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.evaluating_groups.update', $evaluatingGroup->id) }}">
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Anketa</label>
						<select  class="form-control"  name="questionnaire_id">
							<option value="" disabled selected</option>
							@foreach ($questionnaires as $questionnaire)
								<option value="{{ $questionnaire->id }}" {!! $questionnaire->id == $evaluatingGroup->questionnaire['id'] ? 'selected' : '' !!}>{{ $questionnaire->naziv }}</option>
							@endforeach
						</select>
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Naziv kategorije</label>
						<input name="naziv" type="text" class="form-control" value="{{ $evaluatingGroup->naziv }}">
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('koeficijent'))  ? 'has-error' : '' }}">
                        <label>Koeficijent</label>
						<input list="koeficijent" name="koeficijent" class="form-control"  value="{{ $evaluatingGroup->koeficijent }}" required>
						<datalist id="koeficijent" >
							<option value="0.2">
							<option value="0.15">
						</datalist> 
						{!! ($errors->has('koeficijent') ? $errors->first('koeficijent', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					{{ method_field('PUT') }}
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Upiši" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop