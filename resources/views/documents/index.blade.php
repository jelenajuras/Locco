@extends('layouts.admin')

@section('title', 'Dokumenti')
<link rel="stylesheet" href="{{ URL::asset('css/document.css') }}"/>

@section('content')
<div class="dokumenti">
	@if(Sentinel::inRole('administrator'))
		@if (\Session::has('danger'))
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{!! \Session::get('danger') !!}
			</div>
		@endif	
		<div class="upload">
			<h3>Spremi dokumenat</h3>
			<form action="{{ route('admin.documents.store') }}" method="post" enctype="multipart/form-data" style="text-align:left;">
				<div class="form-group">
					<label class="padd_10">Za djelatnika </label>
					<select class="djelatnik" name="employee_id" value="{{ old('employee_id') }}" required>
						<option selected="selected" value=""></option>
						<option name="svi" value="svi">Svi zaposlenici</option>
						@foreach($registrations as $djelatnik)
							@if(!DB::table('employee_terminations')->where('employee_id',$djelatnik->employee_id)->first() )
								<option name="employee_id" value="{{ $djelatnik->employee_id }}">{{ $djelatnik->last_name  . ' ' . $djelatnik->first_name }}</option>
							@endif
						@endforeach	
					</select>
					{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				Izaberi dokument 
				<div class="form-group">
					<input type="file" name="fileToUpload" required>
					<input name="_token" value="{{ csrf_token() }}" type="hidden">
				</div>
				<input type="submit" value="Upload Image" name="submit">
			</form>
		</div>
	@endif
	<div class="documents"> <!-- -->
		@if($docs)
			<h3>Dokumenti djelatnika</h3>
			@foreach($docs as $doc)
				<?php  $myfile = fopen('storage/' . $user_name . '/' . $doc, 'r') or die("Unable to open file!");
				  $path = storage_path($myfile);
				  
					$open = 'storage/' . $user_name . '/' . $doc . '#toolbar=0&scrollbar=0&navpanes=1';
				?>
				<div class="document">
					<p><a href="{{action('DocumentController@generate_pdf', $open ) }}" target="_blank" >
						{{ $doc }}
				</a></p>
				</div>
				<?php fclose($myfile);?>
			@endforeach
		@endif
		@if($docs2)
			<h3>Dokumenti opće</h3>
			@foreach($docs2 as $doc2)
				<?php  $myfile = fopen('storage/svi/' . $doc2, 'r') or die("Unable to open file!");
				  $path = storage_path($myfile);
				  
					$open2 = 'storage/svi/' . $doc2 . '#toolbar=0&scrollbar=0&navpanes=1';
				?>
				<div class="document">
					<p><a href="{{action('DocumentController@generate_pdf', $open2 ) }}" target="_blank" >
						{{ $doc2 }}
				</a></p>
				</div>
				<?php fclose($myfile);?>
			@endforeach
		@endif
	</div>
</div>
@stop
