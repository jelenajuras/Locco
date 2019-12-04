@extends('layouts.admin')

@section('title', 'Nova uputa')

@section('content')
<div class="page-header">
  <h2>Upis nove upute</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.instructions.store') }}">
					<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" id="departments">
						<label>Prima:</label>
						<select class="form-control select_department" name="department_id[]" id="sel1" multiple size="10" >
							@foreach($departments->where('level',0) as $department0)
								<option class="{{ $department0->name }}" value="{{ $department0->id }}">{{ $department0->name }}</option>
							@endforeach
							@foreach($departments->where('level',1) as $department1)
								<option value="{{ $department1->id }}">{{ $department1->name }}</option>
								@foreach($departments->where('level',2) as $department2)
									@if($department2->level1 == $department1->id)
									<option value="{{ $department2->id }}">-  {{ $department2->name }}</option>
									@endif
								@endforeach
							@endforeach
						</select>
						{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
						<label>Naslov:</label>
						<input name="title" type="text" class="form-control" value="{{ old('title') }}" >
						{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
						<label>Opis:</label>
						<textarea name="description" type="text" class="form-control" rows="15">{{ old('description') }}</textarea>

						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Snimi" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
@stop