@extends('layouts.admin')

@section('title', 'Naslovnica')
<link rel="stylesheet" href="{{ URL::asset('css/notices.css') }}" type="text/css" >
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="noticeBoard">
	<a class="gumb_arhiva" href="{{ route('admin.notices.index') }}">Arhiva</a>
	<h1>Oglasna ploča</h1>
	<section class="notices">
		@if(isset($employee))
			@if(count($notices)> 0)
				@foreach($notices as $notice)
					<?php 
						$departments = explode(',', $notice->to_department_id);
					?>
					@foreach ($employee_departments as $employee_department)
						@if(in_array( $employee_department, $departments))
							<article class="notice" style="text-align:left;">
								<h3>{{ $notice->subject }}</h3>
								<p >{!! $notice->notice !!}</p>
							</article>
							<?php break; ?>
						@endif
					@endforeach
				@endforeach	
			@else
				Nema novih obavijesti!
			@endif
		@else  
			@foreach($notices as $notice)
				@if($notice->department['level'] == '0')
					<article class="notice">
						<h3>{{ $notice->subject }}</h3>
						<p style="text-align:left;">{!! $notice->notice !!}</p>
					</article>
				@endif
			@endforeach
		@endif
	</section>
</div>
<script>
$( document ).ready(function() {
  $('.notice span').css("font-size","16");
  $('.notice span').css("font-family","Arial");
  $('.notice span').css("line-height:","20px");
});
</script>
@stop
