@extends('layouts.admin')

@section('title', 'Obavijest')

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
				<h3>{{ $notice->subject }}</h3>
				
				<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ 'Uprava@duplico.hr' }} | <span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($notice->created_at))->diffForHumans() }} </small>
			</div>
            <div class="panel-body">
			{!! $notice->notice !!}
			</div>			
        </div>
		
	</div>

@stop
