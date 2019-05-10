$(document).ready(function() {
	var rola = document.getElementById('rola').value;
	if(rola != "basic"){
		var table = $('#table_id').DataTable( {
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
		var table = $('#table_id').DataTable( {
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