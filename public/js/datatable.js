$(document).ready(function() {
	var rola = document.getElementById('rola').value;
	var kolona = 0;
	var sort = 'asc';
	var paging;
	var lengthMenu;
	var pageLength;

	if ($('#table_id').hasClass('sort_5_desc')) {
		kolona = 4;
		sort = 'desc';
	}
	if ($('#table_id').hasClass('sort_2_desc')) {
		kolona = 1;
		sort = 'desc';
	}
	if ($('#table_id').hasClass('no_paging')) {
		paging = false;
		lengthMenu = '[ 200 ]';
		pageLength = 200;
	} else {
		paging = true;
		lengthMenu = '[ 25, 50, 75, 100 ]';
		pageLength = 50;
	}

	if(rola != "basic"){
		var table = $('#table_id').DataTable( {
		"paging": paging,
		"order": [[ kolona, sort ]],
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
		 "pageLength": pageLength,
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
				columns: ":not(.not-export-column)",
				rows: ':visible'
			}
			},
			],
		} );
	} else {
		var table = $('#table_id').DataTable( {
		"paging": paging,
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
		 "pageLength": pageLength,
		 "columnDefs": [
                { "type": "numeric-comma", targets: 3 }
            ]
	} );
	}
	
	
	$('a.toggle-vis').on( 'click', function (e) {
		e.preventDefault();

		// Get the column API object
		var column = table.column( $(this).attr('data-column') );

		// Toggle the visibility
		column.visible( ! column.visible() );
	} );
});

/* Tablica izostanaka - izlazaka */
$(document).ready(function() {
	var rola = document.getElementById('rola').value;
	if(rola != "basic"){
		var table = $('#tbl_izostanci').DataTable( {
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
		 dom: 'Bfrtip',
			buttons: [
				'copy', 'pdf', 'print',
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
		 $('#tbl_izostanci').DataTable();
	}
	
	
	$('a.toggle-vis').on( 'click', function (e) {
		e.preventDefault();

		// Get the column API object
		var column = table.column( $(this).attr('data-column') );

		// Toggle the visibility
		column.visible( ! column.visible() );
	} );
});