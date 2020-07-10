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
						<option name="svi" value="svi_djelatnici">Svi zaposlenici</option>
						<option name="svi" value="svi_korisnici">Svi korisnici</option>
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
					{{ csrf_field() }}
				</div>
				<input type="submit" value="Upload Image" name="submit">
			</form>
		</div>
	@endif
	<div class="documents"> <!-- -->
		@if( $docs)
			<h3>Dokumenti djelatnika</h3>
			@foreach($docs as $doc)
				<?php  $myfile = fopen('storage/' . $user_name . '/' . $doc, 'r') or die("Unable to open file!");
				  $path = storage_path($myfile);
				  
					$open = 'storage/' . $user_name . '/' . $doc . '#toolbar=0&scrollbar=0&navpanes=1';
				?>
				<div class="document">
					<p><a href="{{action('DocumentController@generate_pdf', $open ) }}" target="_blank" >
						{{ $doc }}
						@if(Sentinel::inRole('administrator'))
							<a href="{{action('DocumentController@deleteDoc', ['path' => 'storage/' . $user_name . '/' . $doc ]) }}" class="action_confirm deleteDoc" >
								<i class="far fa-trash-alt"></i>
							</a>
						@endif
					</a></p>
				</div>
				<?php fclose($myfile);?>
			@endforeach
		@endif
		@if($employee && $docs2)
			<h3>Dokumenti - djelatnici</h3>
			@foreach($docs2 as $doc2)
				<?php  $myfile = fopen('storage/svi_djelatnici/' . $doc2, 'r') or die("Unable to open file!");
				  $path = storage_path($myfile);
				  
					$open2 = 'storage/svi_djelatnici/' . $doc2 . '#toolbar=0&scrollbar=0&navpanes=1';
				?>
				<div class="document">
					<p><a href="{{action('DocumentController@generate_pdf', $open2 ) }}" target="_blank" >
						{{ $doc2 }}
						@if(Sentinel::inRole('administrator'))
							<a href="{{action('DocumentController@deleteDoc', ['path' => 'storage/svi_djelatnici/' . $doc2 ]) }}" class="action_confirm deleteDoc" >
								<i class="far fa-trash-alt"></i>
							</a>
						@endif
				</a></p>
				</div>
				<?php fclose($myfile);?>
			@endforeach
		@endif
		@if($docs3)
			<h3>Dokumenti - korisnici</h3>
			@foreach($docs3 as $doc3)
				<?php  $myfile = fopen('storage/svi_korisnici/' . $doc3, 'r') or die("Unable to open file!");
				  $path = storage_path($myfile);
				  
					$open3 = 'storage/svi_korisnici/' . $doc3 . '#toolbar=0&scrollbar=0&navpanes=1';
				?>
				<div class="document">
					<p><a href="{{action('DocumentController@generate_pdf', $open3 ) }}" target="_blank" >
						{{ $doc3 }}
						@if(Sentinel::inRole('administrator'))
							<a href="{{action('DocumentController@deleteDoc', ['path' => 'storage/svi_korisnici/' . $doc3 ]) }}" class="action_confirm deleteDoc" >
								<i class="far fa-trash-alt"></i>
							</a>
						@endif
				</a></p>
				</div>
				<?php fclose($myfile);?>
			@endforeach
		@endif
	</div>
</div>
<script>

	var url;
	
	$('.deleteDoc').click(function(event){
		event.preventDefault();
		var txt;
		var r = confirm("Želiš li sigurno obrisati dokumenat?");
		if (r == true) {
			
			url = $( this ).attr('href');
			console.log(url);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				url: url,
				type: "get",
				success: function( response ) {
					location.reload();
				},
				error: function(xhr,textStatus,thrownError) {
					console.log(xhr + "\n" + textStatus + "\n" + thrownError);
				}
			});
		}
	});
	

</script>
@stop
