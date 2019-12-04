@extends('layouts.admin')

@section('title', 'Radne upute')

@section('content')
<div class="">
     <div class="page-header">
        <a class="btn btn-md pull-left" href="{{ url()->previous() }}">
            <i class="fas fa-angle-double-left"></i>
            Natrag
        </a>
        @if(Sentinel::inRole('uprava') || Sentinel::inRole('administrator'))
        <div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.instructions.create') }}" id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Unesi novu uputu
            </a>
        </div>
        @endif
        <h2>Radne upute</h2>
    </div>
    <div class="show_instruction">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if(count($employee_instructions) > 0)
                @foreach ($employee_instructions as $instruction) 
                    <a href="{{ route('admin.instructions.show', $instruction->id) }}"><h4>{{ $instruction->title }}<!--<small> | {{ $instruction->department['name'] }}</small>--></h4></a>                  
                @endforeach            
			@else
				{{'Nema unesenih podataka!'}}
            @endif
            </div>
        </div>
    </div>
</div>
@stop
