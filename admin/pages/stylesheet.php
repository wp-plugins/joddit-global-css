<?php
/**
 * @package Admin
 */
 
global $jgcss_admin_pages;
global $wpdb;

if($_GET['stylesheet_id']) {
	
	// Generate the table name
	$table_name = $wpdb->prefix . "jgcss_stylesheets";
	
	// Stylesheets query
	$stylesheet_query = "
		SELECT *
		FROM $table_name
		WHERE stylesheet_id = $_GET[stylesheet_id]";
	
	$stylesheet = $wpdb->get_row($stylesheet_query);
}

$options = get_option( 'jgcss_new_stylesheet' );

$title = ($_GET['stylesheet_id'] ? 'Edit Stylesheet' : 'New Stylesheet');

$jgcss_admin_pages->admin_header( $title, false, array('name' => 'stylesheet', 'action' => site_url() . '/wp-admin/admin.php?page=jgcss_dashboard', 'method' => 'post', 'id' => 'stylesheet') );

?>
<div id="titlediv">
  <div id="titlewrap">
    <label class="hide-if-no-js" id="title-prompt-text"<?php if($_GET[stylesheet_id]) { echo ' style="display: none;"'; } ?> for="title">Enter a Name for Your Stylesheet</label>
    <input type="text" name="name" size="30" tabindex="1" id="title"<?php echo ' value="' . $stylesheet->stylesheet_name . '"'; ?>>
  </div>
</div>
<div id="wp-content-editor-container" class="wp-editor-container">
    <textarea class="wp-editor-area" rows="20" tabindex="1" cols="40" name="content" id="content"><?php echo stripslashes($stylesheet->stylesheet_content); ?></textarea>
</div>
<input type="hidden" name="submitted" value="1" />
<?php

if($_GET['stylesheet_id']) {
	echo '<input type="hidden" name="stylesheet_id" value="' . $_GET[stylesheet_id] . '" />';
}

do_action( 'jgcss_new_stylesheet' );

$jgcss_admin_pages->admin_footer( array('button_label' => 'Save Stylesheet') );

?>
