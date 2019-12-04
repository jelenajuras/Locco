@extends('layouts.admin')

@section('title', 'Radne upute')

@section('content')
<div class="instruction_show ">
    <div class="page-header">
        <a class="btn btn-md pull-left" href="{{ url()->previous() }}">
            <i class="fas fa-angle-double-left"></i>
            Natrag
        </a>
        <h2>{{ $instruction->title }}<br><small>Odjel: {{  $instruction->department['name'] }}</small></h2>
        
    </div>
    <div class="card_instr col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2" >
            <pre>{!! $instruction->description !!}</pre>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 comments">
        <h5>Komentiraj!</h5>
        <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.comment_instructions.store', $instruction->id) }}">
            <input type="hidden" name="instruction_id" value="{{ $instruction->id }}" >
            <div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
               <textarea class="form-control" name="content" id="post-content" rows="5"></textarea>
                {!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            {{ csrf_field() }}        
        <input class="btn btn-lg btn-primary" type="submit" value="Spremi komentar" id="stil1">
        </form>
    </div>
    @if( $uprava == true )
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3  comments">
            <h4 id="Comments">Komentari</h4>
            @if(count($instruction->comments()) > 0)
                @foreach ($instruction->comments() as $comment)
                    <div class="media">
                    
                        <div class="media-body">
                            <h5 class="media-heading"><small>{{ $comment->employee['email'] }} | {{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}</small></h5>
                                    
                            {{ $comment->content}}
                        </div>
                    </div>
                    <hr>
                @endforeach	
                {!! $instruction->comments()->links('vendor.pagination.comments') !!}
            @else		
                <p>{{'No Comments!'}}</p>	
            @endif
        </div>
    @endif
</div>
@stop
