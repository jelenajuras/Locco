@extends('layouts.admin')

@section('title', 'Poruke')
<link rel="stylesheet" href="{{ URL::asset('css/vacations.css') }}" type="text/css" />
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
</a>
<div class="" >
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <a class="btn btn-primary btn-lg" href="{{ route('admin.posts.create') }}" id="stil1">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Pošalji poruku
            </a>
        </div>
        <h1>Poruke</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($posts) > 0)
					<table id="table_post" class="display" style="width: 100%;">
						<thead>
							<tr>
								<th>Datum</th>
								<th>Title</th>
								<th>Šalje</th>
								<th>Prima</th>
								<th class="not-export-column">Options</th>
							</tr>
						</thead>
						<tbody id="myTable">
							@foreach ($posts as $post)
								<tr>
									<td>{{ date('Y.m.d',strtotime($post->created_at)) }}</td>
									<td>
										<a href="{{ route('admin.posts.show', $post->id) }}">
											{{ $post->title }}
										</a>
									</td>
									<td>{{ $post->user['first_name'] . ' ' . $post->user['last_name']}}</td>
									<td>{{ $post->department['name'] }}</td>
									<td>
										<a href="{{ route('admin.posts.edit', $post->id)}}">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
										</a>
										@if(Sentinel::inRole('uprava'))
										 <a href="{{ route('admin.posts.destroy', $post->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					{{'Nema poruka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
	var rola = document.getElementById('rola').value;
	if(rola != "basic"){
		var table = $('#table_post').DataTable( {
		"paging": true,
		language: {
			paginate: {
				previous: 'Prethodna',
				next:     'Slijedeća',
			},
			"info": "Prikaz _START_ do _END_ od _TOTAL_ zapisa",
			"search": "Filtriraj:",
			"lengthMenu": "Prikaži _MENU_ zapisa"
		},
		 "lengthMenu": [ 25, 50, 75, 100 ],
		 "pageLength": 50,
		 "order": [[ 0, "desc" ]],
		 dom: 'Bfrtip',
			buttons: [
				'copy', 'pdf', 'print',
			/*{
				extend: 'pdfHtml5',
				text: 'Izradi PDF',
				exportOptions: {
					columns: ":not(.not-export-column)"
					}
				},*/
				{
			extend: 'excelHtml5',
			text: 'Izradi XLS',
			
			exportOptions: {
				columns: ":not(.not-export-column)"
			}
			},
			],
	} );
	} else {
		 $('#table_id').DataTable();
	}
	
	
	$('a.toggle-vis').on( 'click', function (e) {
		e.preventDefault();

		// Get the column API object
		var column = table.column( $(this).attr('data-column') );

		// Toggle the visibility
		column.visible( ! column.visible() );
	} );
});
</script>
@stop
