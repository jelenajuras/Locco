@extends('layouts.admin')

@section('title', 'Ispravak upute')

@section('content')
<div class="page-header">
  <h2>Ispravak upute</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.instructions.update', $instruction->id) }}">
					<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" id="departments">
						<label>Prima:</label>
						<select class="form-control select_department" name="department_id" id="sel1" required>
							@foreach($departments->where('level',0) as $department0)
								<option class="{{ $department0->name }}" value="{{ $department0->id }}" {!! $department0->id == $instruction->department_id ? 'selected' : '' !!}>{{ $department0->name }}</option>
							@endforeach
							@foreach($departments->where('level',1) as $department1)
								<option value="{{ $department1->id }}" {!!  $department1->id == $instruction->department_id ? 'selected' : '' !!} >{{ $department1->name }}</option>
								@foreach($departments->where('level',2) as $department2)
									@if($department2->level1 == $department1->id)
									<option value="{{ $department2->id }}"  {!!  $department2->id == $instruction->department_id ? 'selected' : '' !!} >-  {{ $department2->name }}</option>
									@endif
								@endforeach
							@endforeach
						</select>
						{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
						<label>Naslov:</label>
						<input name="title" type="text" class="form-control" value="{{ $instruction->title }}" >
						{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
						<label>Opis:</label>
						<textarea name="description" type="text" class="form-control" rows="15">{{ $instruction->description }}</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('active')) ? 'has-error' : '' }}">
						<p><label>Status:</label></p>
						<label class="status" for="active_1">Aktivna 
							<input name="active" id="active_1" type="radio" value="1" {!! $instruction->active == 1 ? 'checked' : '' !!} />
						</label>
						<label class="status" for="active_0">Neaktivna
							<input name="active" type="radio" value="0" id="active_0"  {!! $instruction->active != 1 ? 'checked' : '' !!} />
						</label>
						{!! ($errors->has('active') ? $errors->first('active', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Snimi" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
@stop