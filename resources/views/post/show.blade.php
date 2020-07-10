@extends('layouts.index')

@section('title')
{{ $post->title }}
@endsection

@section('content')
    <div class="page-header">
        <div class='btn-toolbar'>
            <a class="btn btn-primary btn-lg" href="{{ url('/') }}">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                Go Back
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-md-offset-1">
            <div class="panel-heading">
				<h1>{{ $post->title }}</h1>
				
				<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $post->user->email }} | <span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }} </small>
			</div>
            <div class="panel-body">
			{!! $post->content !!}
			</div>			
        </div>
    </div>
	@if(Sentinel::check())
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-md-offset-1">
				<h2>Leave a comment!</h2>
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('comment.store', $post->id) }}">
					<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
                       <textarea class="form-control" name="content" id="post-content" style="height:200px"></textarea>
                        {!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
				{{ csrf_field() }}
				<input type="hidden" name="post_id" value="{{$post->id }}">
				<input class="btn btn-lg btn-primary" type="submit" value="Save">
				</form>
			</div>
		</div>

	@else
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-md-offset-1">
				<div class="well">
					Please <a href="{{ route('auth.login.form') }}">Sign In</a> or <a href="{{ route('auth.register.form') }}">Sign Up</a> to leave a comment.
				</div>
			</div>
		</div>
	@endif
		
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-md-offset-1">
			<h2 id="Comments">Comments</h2>
		@if(count($post->comments()) > 0)
			@foreach ($post->comments() as $comment)
				<div class="media">
					<div class="media-left">
						<a href="#">
							<img class="media-object" src="//www.gravatar.com/avatar/{{ md5($comment->user->email) }}?d=mm">
							</a>
					</div>
					<div class="media-body">
						<h5 class="media-heading">{{ $comment->user->email }} | <small>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }} </small></h5>
								
						{{ $comment->content}}
					</div>
				</div>
				<hr>
			@endforeach	
			{!! $post->comments()->links('vendor.pagination.comments') !!}
		@else		
			<p>{{'No Comments!'}}</p>	
		@endif
		</div>
	</div>
		
@stop