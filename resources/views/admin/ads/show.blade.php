@extends('layouts.admin')

@section('title', 'Oglas')
<link rel="stylesheet" href="{{ URL::asset('css/create.css') }}" type="text/css" >
@section('content')
    <div class="" >
        <div class='btn-toolbar'>
            <a class="btn btn-lg" href="{{ url()->previous() }}">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                Natrag
            </a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div class="panel-heading">
				<h3>{{ $ad->subject }}</h3>
				<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $ad->employee['first_name'] }} | <span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($ad->created_at))->diffForHumans() }} </small>
			</div>
            <div class="panel-body" style="text-align:left;">
				{!! $ad->description !!}
				@foreach($docs as $doc)
					<img src="{{ asset('storage/oglas/' . $ad->id . '/' . $doc) }}" />
				@endforeach
			</div>			
        </div>
	</div>
@stop
