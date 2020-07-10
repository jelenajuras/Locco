@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="page-header">
  <h2>Ispravi dozvolu</h2>
</div> 
<div class="">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('roles.update', $role->id) }}">
                <fieldset>
                    <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Name" name="name" type="text" value="{{ $role->name }}" />
                        {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('slug')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="slug" name="slug" type="text" value="{{ $role->slug }}" />
                        {!! ($errors->has('slug') ? $errors->first('slug', '<p class="text-danger">:message</p>') : '') !!}

                    <h5>Dozvole:  <input type="checkbox" name="select-all" id="select-all"/> select all</h5>
						<div class="flex-container">
							@foreach($tables as $table)
								@foreach($permissions as $permission)
									<div class="checkbox col-lg-3 col-md-6 col-sm-12">
										<span name="checkbox">
											<input type="checkbox" name="{{ 'permissions[' . $table->name . '.' . $permission . ']' }}" value="1" {!! $role->hasAccess($table->name . '.' . $permission) ? 'checked' : '' !!} >
												{{ $table->name . '.' . $permission }}
										</span>
									</div>
								@endforeach
							@endforeach
						</div>

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Update" id="stil1">
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
