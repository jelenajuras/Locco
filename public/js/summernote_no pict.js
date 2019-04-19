$(document).ready(function() {
  $('#summernote').summernote({
	  toolbar: [
		['fontsize', ['fontsize']],
		['font', ['bold', 'italic', 'underline', 'clear']],
		['fontname', ['fontname']],
		['color', ['color']],
		['para', ['ul', 'ol', 'paragraph']],
		['height', ['height']],
		['table', ['table']]
	  ],
	  height: 200,
	  tabsize: 3
  });
});