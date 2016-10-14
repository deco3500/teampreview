/**
 * Show pop on marker click and set the pop up title to value
 */
$(document).on('click', '#moreinfo', function() {
	$('#popup').popup('show');
	$('#popup-title').text($('#moreinfo').val());
});
/**
 * Hide pop up;
 */
$(document).on('click', '#close', function() {

	$('#popup').popup('hide');
});

