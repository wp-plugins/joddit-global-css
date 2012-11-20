jQuery(document).ready(function ($) {
	
	// Handles the enabling and disabling of the stylesheet name placeholder
	$('#title').focus(function(){
		$('#title-prompt-text').hide();
	});
	
	$('#title').blur(function(){
		if($('#title').val() == "") {
			$('#title-prompt-text').show();
		}
	});
	
});