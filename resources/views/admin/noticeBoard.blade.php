@extends('layouts.admin')

@section('title', 'Naslovnica')
<link rel="stylesheet" href="{{ URL::asset('css/notices.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="noticeBoard">
	<h1>Oglasna ploča</h1>
	<section class="notices">
		@foreach($employee_departments as $employee_department)
			@foreach($notices as $notice)
				@if($notice->department['level'] == '2')
					@if($notice->to_department_id == $employee_department->department_id )
							<article class="notice" style="text-align:left;">
								<h3>{{ $notice->subject }}</h3>
								<p >{!! $notice->notice !!}</p>
							</article>
					@endif
				@else
					@if($notice->to_department_id == $employee_department->department['level1'] || $notice->department['level'] == '0' )
							<article class="notice">
								<h3>{{ $notice->subject }}</h3>
								<p style="text-align:left;">{!! $notice->notice !!}</p>
							</article>
					@endif
				@endif
			@endforeach
		@endforeach
	</section>
</div>
@stop
