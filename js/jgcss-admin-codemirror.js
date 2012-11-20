jQuery(document).ready(function ($) {
	// Create the CodeMirror CSS object instance
	var cssEditor = CodeMirror.fromTextArea(document.getElementById('content'), {
		theme: 'eclipse',
		lineNumbers: true,
		matchBrackets: true
	});
});