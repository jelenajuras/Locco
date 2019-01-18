@extends('layouts.admin')

@section('title', 'Ispravak podkategorije')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="page-header">
  <h2>Ispravak podkategorije</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.evaluating_questions.update', $evaluatingQuestion->id) }}">
					<div class="form-group {{ ($errors->has('group_id'))  ? 'has-error' : '' }}">
						<label>Kategorija pitanja</label>
						<select name="group_id" class="form-control" >
							<option disabled selected></option>
							@foreach($evaluatingGroups as $evaluatingGroup)
								<option value="{{ $evaluatingGroup->id }}"  {!! $evaluatingGroup->id == $evaluatingQuestion->group_id ? 'selected' : '' !!}>{{ $evaluatingGroup->naziv }}</option>
							@endforeach
						</select>
						{!! ($errors->has('group_id') ? $errors->first('group_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('naziv'))  ? 'has-error' : '' }}">
                        <label>Podkategorija</label>
						<input name="naziv" type="text" class="form-control" value="{{ $evaluatingQuestion->naziv }}" />
						{!! ($errors->has('naziv') ? $errors->first('naziv', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('opis'))  ? 'has-error' : '' }}">
                        <label>Opis</label>
						<textarea name="opis" type="text" class="form-control" rows="6" value="">{{ $evaluatingQuestion->opis  }}</textarea>
						{!! ($errors->has('opis') ? $errors->first('opis', '<p class="text-danger">:message</p>') : '') !!}
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