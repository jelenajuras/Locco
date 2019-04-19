@extends('layouts.admin')

@section('title', 'Ispravi tablicu')

@section('content')
<div class="page-header">
  <h2>Ispravak tablice</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				 <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.tables.update', $tbl->id) }}">
					<div class="form-group">
                        <label>Naziv tablice</label>
						<input name="name" type="text" class="form-control" value="{{ $tbl->name }}" required >
                    </div>
					<div class="form-group">
                        <label>Opis</label>
						<input name="description" type="text" class="form-control" value="{{  $tbl->description }}" >
                    </div>
					
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Ispravi podatke" id="stil1">
				</form>
			</div>
		</div>
	</div>
</div>

@stop