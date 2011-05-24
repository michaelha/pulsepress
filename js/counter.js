jQuery(function($) {
	$("#posttext").keypress(function() {
		var length = $(this).val().length;
		var remainder = 140 - length;
 	$('#post-count').html(remainder);
});


});