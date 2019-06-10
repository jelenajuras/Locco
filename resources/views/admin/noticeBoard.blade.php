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
			@foreach($notices as $notice)
				@if($notice->department['level'] == '2')
					@if($employee_departments->where('department_id', $notice->to_department_id)->first() )
						<article class="notice" style="text-align:left;">
							<h3>{{ $notice->subject }}</h3>
							<p >{!! $notice->notice !!}</p>
						</article>
					@endif
				@else
					@if($notice->department['level'] == '0')
						<article class="notice" style="text-align:left;">
							<h3>{{ $notice->subject }}</h3>
							<p >{!! $notice->notice !!}</p>
						</article>
					@endif
					@foreach($employee_departments as $employee_department)
						@if($notice->to_department_id == $employee_department->department['level1'] )
							<article class="notice">
								<h3>{{ $notice->subject }}</h3>
								<p style="text-align:left;">{!! $notice->notice !!}</p>
							</article>
						@endif
					@endforeach
				@endif
			@endforeach
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
