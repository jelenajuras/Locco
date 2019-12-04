@extends('layouts.admin')

@section('title', 'Sastanak')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
<div class="page-header">
  <h2>Upis novog sastanak</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.meetings.update', $meeting->id) }}">
					<div class="datum1 form-group {{ ($errors->has('datum')) ? 'has-error' : '' }}">
						<label>Datum:</label>
						<input name="datum" class="date form-control" type="text" value = "{{  date('d-m-Y', strtotime($meeting->datum)) }}" >
						<i class="far fa-calendar-alt"></i>
						{!! ($errors->has('datum') ? $errors->first('datum', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="datum2 form-group">
						<span>od</span><input type="text" name="vrijeme_od" class="timepicker form-control" value="{{ $meeting->vrijeme_od }}">
						<span>do</span><input type="text" name="vrijeme_do" class="timepicker form-control" value="{{ $meeting->vrijeme_do }}" >
					</div>
					<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
						<label class="padd_10">Djelatnik</label>
						<select class="form-control" name="employee_id" value="{{ old('employee_id') }}">
							@foreach($registrations as $djelatnik)
								@if(!DB::table('employee_terminations')->where('employee_id',$djelatnik->employee_id)->first() )
									<option name="employee_id" value="{{ $djelatnik->employee_id }}" {!! $meeting->employee_id == $djelatnik->employee_id ? 'selected' : '' !!} >{{ $djelatnik->last_name  . ' ' . $djelatnik->first_name }}</option>
								@endif
							@endforeach	
						</select>
						{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('meeting_room_id')) ? 'has-error' : '' }}">
						<label class="padd_10">Soba za sastanak</label>
						<select class="form-control" name="meeting_room_id" value="{{ old('meeting_room_id') }}">
							<option selected="selected" value=""></option>
							
							@foreach($meeting_rooms as $meeting_room)
								<option name="meeting_room_id" value="{{ $meeting_room->id }}" {!! $meeting->meeting_room_id == $meeting_room->id ? 'selected' : '' !!}>{{ $meeting_room->name  . ' - ' . $meeting_room->description }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('meeting_room_id') ? $errors->first('meeting_room_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('project_id')) ? 'has-error' : '' }}">
						<label class="padd_10">Projekt</label>
						<select class="form-control" name="project_id" value="{{ old('meeting_room_id') }}">
							<option selected="selected" value=""></option>
							@foreach($projects as $project)
								<option name="project_id" value="{{ $project->id }}" {!! $meeting->project_id == $project->id ? 'selected' : '' !!}>{{ $project->id  . ' - ' . $project->naziv }}</option>
							@endforeach	
						</select>
						{!! ($errors->has('project_id') ? $errors->first('project_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
						<label>Opis:</label>
						<input name="description" type="text" class="form-control" value="{{ $meeting->description }}" >
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ method_field('PUT') }}
					{{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="ispravi" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.date').datepicker({  
	   format: 'yyyy-mm-dd',
	   startDate:'-60y',
	   endDate:'+1y',
	}); 
</script> 
<script>
$(document).ready(function(){
    $('input.timepicker').timepicker({ 
		timeFormat: 'HH:mm',
		dropdown: true,
		scrollbar: true
	});
});
</script> 

</script> 
@stop