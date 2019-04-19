@extends('layouts.admin')

@section('title', 'Create New Role')

@section('content')
<div class="page-header">
  <h2>Upiši novu dozvolu</h2>
</div> 
<div class="">

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('roles.store') }}">
                <fieldset>
                    <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Name" name="name" type="text" value="{{ old('name') }}" />
                        {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('slug')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="slug" name="slug" type="text" value="{{ old('slug') }}" />
                        {!! ($errors->has('slug') ? $errors->first('slug', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					
                    <h5>Permissions: <input type="checkbox" name="select-all" id="select-all"/> select all</h5>
					<div class="flex-container">
						@foreach($tables as $table)
							@foreach($permissions as $permission)
								<div class="checkbox col-lg-3 col-md-6 col-sm-12">
									<span name="checkbox">
										<input type="checkbox" name="{{ 'permissions[' . $table->name . '.' . $permission . ']' }}" value="1">
											{{ $table->name . '.' . $permission }}
									</span>
								</div>
							@endforeach
						@endforeach
					</div>
					
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Create" id="stil1">
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});
</script>
@stop
