var dictLookup = function() {
	var term = $('#term').val();
	var data = $('form#search').serialize();

	if (term !== '') {
		console.log('not blank');
		$.ajax({
			url: 'query_dict.php',
			type: "GET",
			data: data,
			success: function(html) {
				$('#result').html(html);
				}
		});
	}
}

$.ajaxSetup({
	scriptCharset: "utf-8" ,
	contentType: "application/json; charset=utf-8"
});

$(document).on("keyup", "#term", dictLookup);
$(document).on("change", "input[name='chartype']", dictLookup);

$(document).ready(function() {
	$('#search').focus();
});