@extends('layouts.admin')

@section('title', 'Posts')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}"/>
@section('content')
    <div class="post" >
        <div class='btn-toolbar'>
            <a class="btn btn-primary btn-lg" href="{{ url()->previous() }}"  id="stil1">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                Natrag
            </a>
			
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div class="panel-heading">
				<h3>{{ $post->title }}</h3>
				
				<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $post->user->email }} | <span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }} </small>
			</div>
            <div class="panel-body">
			{!! $post->content !!}
			</div>			
        </div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
			<h5>Komentiraj!</h5>
			<form accept-charset="UTF-8" role="form" method="post" action="{{ route('comment.store', $post->id) }}">
				<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
				   <textarea class="form-control" name="content" id="post-content" style="height:200px"></textarea>
					{!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			{{ csrf_field() }}
			<input type="hidden" name="post_id" value="{{$post->id }}">
			<input class="btn btn-lg btn-primary" type="submit" value="Spremi komentar" id="stil1">
			</form>
		</div>
	
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-md-offset-1">
			<h4 id="Comments">Komentari</h4>
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
