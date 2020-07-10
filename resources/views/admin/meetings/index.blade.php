@extends('layouts.admin')

@section('title', 'Sastanci')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" >
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@section('content')
<div class="">
    <div class="page-header">
		<div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.meetings.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi
            </a>
        </div>
        <h1>Sastanci</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($meetings) > 0)
                 <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Datum</th>
							<th>Vrijeme</th>
							<th>Djelatnik</th>
							<th>Soba</th>
							<th>Projekt</th>
							<th>Opis</th>
                            <th class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($meetings as $meeting)
                            <tr>
                                <td>{{ date('d.m.Y.', strtotime($meeting->datum)) }}</td>
								<td>{{ $meeting->start_time . '-'. $meeting->end_time }}</td>
								<td>{{ $meeting->employee['first_name'] . ' ' . $meeting->employee['last_name']}}</td>
								<td>{{ $meeting->meetingRoom['name'] . '-'. $meeting->meetingRoom['description'] }}</td>
								<td>{{ $meeting->project_id . ' ' . $meeting->project['naziv']}}</td>
								<td>{{ $meeting->description }}</td>
                                <td>
                                    <a href="{{ route('admin.meetings.edit', $meeting->id) }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    <a href="{{ route('admin.meetings.destroy', $meeting->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
				@else
					{{'Nema podataka!'}}
				@endif
            </div>
        </div>
    </div>
	<div class="raspored">
		<h3>Raspored korištenja soba za sastanke</h3>
		
		<form accept-charset="UTF-8" role="form" method="get" action="{{ route('admin.showKalendar') }}">
			<p>Izbor mjeseca </p>
			<input class="date date-own form-control" type="text" name="mjesec">
			<button type="submit" value="Odaberi" >Odaberi</button>
			<script type="text/javascript">
			$('.date-own').datepicker({
				minViewMode: 1,
				format: 'm-yyyy'
			});
			</script>
		</form>
	</div>
</div>
@stop
