@if (! Sentinel::inRole('erp_test'))
	
@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="page-header" >
        <div class='btn-toolbar pull-right'>
            <a class="btn btn-primary btn-lg" href="{{ route('users.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Create User
            </a>
        </div>
        <h1>Users</h1>
        <input type="text" class="mySearch" id="mySearch" placeholder="Search..." title="Type in...">
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @foreach ($users as $user)
                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                    <div class="panel panel-default ">
                        <div class="panel-body text-center">
                            <img src="//www.gravatar.com/avatar/{{ md5($user->email) }}?d=mm" alt="{{ $user->email }}" class="img-circle">
                            @if (!empty($user->first_name . $user->last_name))
                                <h4>{{ $user->first_name . ' ' . $user->last_name}}</h4>
                                <p>{{ $user->email }}</p>
                            @else
                                <h4>{{ $user->email }}</h4>
                            @endif
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">
                            @if ($user->roles->count() > 0)
                                {{ $user->roles->implode('name', ', ') }}
                            @else
                                <em>No Assigned Role</em>
                            @endif
                            </li>
                        </ul>
                        <div class="panel-footer">
                            <a href="{{ route('users.edit', $user->id) }}" >
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                               
                            </a>
                            <a href="{{ route('users.destroy', $user->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
                                <i class="far fa-trash-alt"></i>
                                
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
	<script>
        
    $( document ).ready(function(){
        $("#mySearch").keyup( function() {
            var value = $(this).val().toLowerCase();
            $(".panel").parent().filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    </script>
@stop
@endif