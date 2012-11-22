jQuery(document).ready(function ($) {
	
	// Handles the enabling and disabling of the stylesheet name placeholder
	$('#title').focus(function() {
		$('#title-prompt-text').hide();
	});
	
	$('#title').blur(function() {
		if($('#title').val() == "") {
			$('#title-prompt-text').show();
		}
	});
	
	// Confirm deletion of stylesheets
	$('.trash a').click(function() {
		var trashConfirm = confirm("Are you sure you want to delete this stylesheet?");
		if(trashConfirm) {
			return true;
		} else {
			return false;
		}
	});
});
