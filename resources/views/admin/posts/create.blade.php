@extends('layouts.admin')

@section('title', 'Nova poruka')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Nova poruka</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.posts.store') }}">
                    @if(Sentinel::inRole('administrator'))
                        <div class="form-group">
                            <label>Tip poruke:</label>
                            <select class="form-control" name="tip">
                                <option selected disabled></option>
                                <option value="prijava">Prijava</option>
                                <option value="prijava">Odjava</option>
                            </select>
                        </div>
                    @endif
                    <div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
                        <label>Prima:</label>
                        @if(Sentinel::inRole('administrator'))
                            <select class="form-control" name="to_department_id[]" id="sel1" multiple size="10" required >
                                @foreach($departments->where('level',0) as $department0)
                                    <option value="{{ $department0->id }}">{{ $department0->name }}</option>
                                @endforeach
                                @foreach($departments->where('level',1) as $department1)
                                    <option value="{{ $department1->id }}">{{ $department1->name }}</option>
                                    @foreach($departments->where('level',2) as $department2)
                                        @if($department2->level1 == $department1->id)
                                        <option value="{{ $department2->id }}">-  {{ $department2->name }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        @else
                            <select class="form-control" name="to_department_id[]" id="sel1" value="{{ old('to_employee_id') }}" required>
                                <option selected value="">Prima...</option>
                                <option value="{{ $departments->where('email','uprava@duplico.hr')->first()->id }}">{{ $departments->where('email', 'uprava@duplico.hr')->first()->name }}</option>
                                <option value="{{ $departments->where('email','pravni@duplico.hr')->first()->id }}">{{ $departments->where('email','pravni@duplico.hr')->first()->name }}</option>
                                <option value="{{ $departments->where('email','itodrzavanje@duplico.hr')->first()->id }}">{{ $departments->where('email', 'itodrzavanje@duplico.hr')->first()->name }}</option>
                                <option value="{{ $departments->where('email', 'racunovodstvo@duplico.hr')->first()->id }}">{{ $departments->where('email','racunovodstvo@duplico.hr')->first()->name }}</option>
                              
                            </select>
                        @endif
                        {!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
						<input class="form-control" placeholder="Post title" maxlength="255" name="title" type="text" value="{{ old('title') }}" />
                        {!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
                       <textarea class="form-control" name="content" id="post-content" maxlength="65535" required></textarea>
						
                        {!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
                    </div>

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Pošalji" id="stil1">
                </form>
            </div>
        </div>
    </div>
</div>
@stop
