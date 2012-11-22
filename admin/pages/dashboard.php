<?php
/**
 * @package Admin
 */

global $jgcss_admin_pages;

$options = get_option( 'jgcss' );

$jgcss_admin_pages->admin_header(
	'Dashboard',
	array('label' => 'Add New', 'url' => site_url() . '/wp-admin/admin.php?page=jgcss_stylesheet'),
	array('name' => 'bulkaction', 'action' => site_url() . '/wp-admin/admin.php?page=jgcss_dashboard&amp;bulkaction=1', 'method' => 'post', 'id' => 'bulkaction')
);

echo '<h2>' . 'Stylesheets' . '</h2>';

// Prepare Table of elements
$stylesheet_list_table = new JGCSS_List_Table();
$stylesheet_list_table->prepare_items();

// Spit out the table
$stylesheet_list_table->display();

do_action( 'jgcss_dashboard' );

$jgcss_admin_pages->admin_footer( false );

?>
