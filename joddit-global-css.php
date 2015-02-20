<?php
/*
Plugin Name: Joddit Global CSS
Plugin URI: http://www.joddit.com
Description: Simple Custom CSS in WordPress: Create and manage custom stylesheets with a powerful CSS editor based on the <a href="http://codemirror.net">CodeMirror</a> JavaScript component.
Version: 1.0.2
Author: Joddit Web Services, LLC
Author URI: http://www.joddit.com
License: GPL2
*/

if ( !defined('JGCSS_URL') )
	define( 'JGCSS_URL', plugin_dir_url( __FILE__ ) );
if ( !defined('JGCSS_PATH') )
	define( 'JGCSS_PATH', plugin_dir_path( __FILE__ ) );
if ( !defined('JGCSS_BASENAME') )
	define( 'JGCSS_BASENAME', plugin_basename( __FILE__ ) );
	
define( 'JGCSS_VERSION', '1.0.2' );

/**
 * Used to load the required files on the plugins_loaded hook, instead of immediately.
 */
function jgcss_frontend_init() {
	
	// Bring wpdb into scope
	global $wpdb;
	
	// Generate the table name
	$table_name = $wpdb->prefix . "jgcss_stylesheets";

	// Build a query to grab the stylesheet data
	$query = "
		SELECT `stylesheet_name`, `stylesheet_file_path`
		FROM `$table_name`
		WHERE `stylesheet_status` = 1";
	
	// Get the stylesheet data
	$stylesheets = $wpdb->get_results($query);
	
	foreach($stylesheets as $stylesheet) {
		
		// Generate a slug
		$slug = 'jgcss-' . sanitize_title($stylesheet->stylesheet_name);
		
		// Enqueue the stylesheet
		wp_enqueue_style( $slug, site_url('/') . $stylesheet->stylesheet_file_path, '', JGCSS_VERSION, 'all' );
	}
	
	// Get the author information
	$user_data = get_userdata($stylesheet_data->stylesheet_author);
	
}

/**
 * Used to load the required files on the plugins_loaded hook, instead of immediately.
 */
function jgcss_admin_init() {
	
	// Include our own custom WP_List_Table from WordPress release 3.9.3
	require_once( JGCSS_PATH . 'includes/class-wp-list-table-3-9-3.php');
	
	// Include other init related includes
	require_once( JGCSS_PATH . 'admin/class-admin.php' );
	require_once( JGCSS_PATH . 'admin/class-config.php' );
	require_once( JGCSS_PATH . 'admin/class-list-table.php' );
}

/**
 * Load any admin resources
 */
function jgcss_load_admin_resources() {
	wp_enqueue_style( 'jgcss-admin', JGCSS_URL . 'css/jgcss-admin.css', '', JGCSS_VERSION, 'all' );
	if($_GET['page'] == 'jgcss_dashboard' || $_GET['page'] == 'jgcss_stylesheet') {
		wp_enqueue_script( 'jgcss-admin-js', JGCSS_URL . 'js/jgcss-admin-general.js', array( 'jquery' ), JGCSS_VERSION );
	}
	
	// Includes the CodeMirror syntax highlighter
	if($_GET['page'] == 'jgcss_dashboard') {
		if($_POST['submitted'] && $_POST['stylesheet_id']) {
			update_stylesheet($_POST);
		} elseif($_GET['stylesheet_id'] && $_GET['action'] == 'trash') {
			delete_stylesheet($_GET['stylesheet_id']);
		} elseif($_GET['bulkaction'] && $_POST['action'] == 'delete') {
			if (is_array($_POST['stylesheet'])) {
				foreach($_POST['stylesheet'] as $stylesheet_id) {
					delete_stylesheet($stylesheet_id);
				} // end foreach
			} // end if
		} elseif($_POST['submitted']) {
			create_stylesheet($_POST);
		}
	}
	
	// Includes the CodeMirror syntax highlighter
	if($_GET['page'] == 'jgcss_stylesheet') {
		wp_enqueue_style( 'jgcss-admin-codemirror', JGCSS_URL . '/lib/codemirror-4.12/lib/codemirror.css', '', '4.12', 'all' );
		wp_enqueue_script( 'jgcss-admin-codemirror', JGCSS_URL . '/lib/codemirror-4.12/lib/codemirror.js', array( 'jquery' ), '4.12' );
		wp_enqueue_script( 'jgcss-admin-codemirror-mode', JGCSS_URL . '/lib/codemirror-4.12/mode/css/css.js', array( 'jgcss-admin-codemirror' ), '4.12' );
		wp_enqueue_script( 'jgcss-admin-codemirror-instance', JGCSS_URL . 'js/jgcss-admin-codemirror.js', array( 'jgcss-admin-codemirror-mode' ), JGCSS_VERSION );
		wp_enqueue_style( 'jgcss-admin-codemirror-eclipse-theme', JGCSS_URL . '/lib/codemirror-4.12/theme/eclipse.css', '', '4.12', 'all' );
		
	}
}

add_action( 'admin_enqueue_scripts', 'jgcss_load_admin_resources' );

/**
 * Installs the base configuration for the plugin
 */
function jgcss_activate() {
	
	// Bring wpdb into scope
	global $wpdb;
	
	// Generate the table name and associated creation query
	$table_name = $wpdb->prefix . "jgcss_stylesheets";
	$install_query = "
		CREATE TABLE IF NOT EXISTS `$table_name` (
		  `stylesheet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `stylesheet_name` varchar(32) NOT NULL,
		  `stylesheet_author` int(10) unsigned NOT NULL,
		  `stylesheet_content` longtext NOT NULL,
		  `stylesheet_status` tinyint(1) NOT NULL DEFAULT '1',
		  `stylesheet_file_path` varchar(255) NOT NULL,
		  `stylesheet_date` datetime NOT NULL,
		  `stylesheet_modified` datetime NOT NULL,
		PRIMARY KEY (`stylesheet_id`))";
	
	// Run the query
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($install_query);
	
	// Add a version option value to the db
	add_option("jgcss_db_version", JGCSS_VERSION);
}

/**
 * Load up the appropriate data
 **/
if ( is_admin() ) {
	add_action( 'plugins_loaded', 'jgcss_admin_init', 0 );
	
	register_activation_hook( __FILE__, 'jgcss_activate' );
	// register_deactivation_hook( __FILE__, 'jgcss_deactivate' );
} else {	
	add_action( 'plugins_loaded', 'jgcss_frontend_init', 0 );
}

/**
 * Insert a new stylesheet into the database
 * @param array the $_POST data from the form submission
 */
function create_stylesheet($submitted_data) {
	
	// Bring wpdb into scope
	global $wpdb;
	
	// Generate the table name
	$table_name = $wpdb->prefix . "jgcss_stylesheets";
	
	// Check if the stylesheet name is blank, if it is, force a name
	if($submitted_data['name'] == '') {
		$submitted_data['name'] = 'Unnamed Stylesheet';
	}
	
	// Get the current user id
	$author = get_current_user_id();
		
	// Construct the data for insertion
	$insert_data = array(
		'stylesheet_name' => $submitted_data['name'],
		'stylesheet_author' => get_current_user_id(),
		'stylesheet_content' => $submitted_data['content'],
		'stylesheet_status' => 1,
		'stylesheet_date' => current_time( 'mysql' ),
		'stylesheet_modified' => current_time( 'mysql' )
	);
	
	// Create an array of insertion formats
	$insert_formats = array(
		'%s',
		'%d',
		'%s',
		'%d',
		'%s',
		'%s'
	);
	
	// Insert the new stylesheet into the db
	$wpdb->insert( $table_name, $insert_data, $insert_formats );
	
	// Update the stylesheets cache file
	cache_stylesheet($wpdb->insert_id);
	
}

/**
 * Update an existimg stylesheet in the database
 * @param array $submitted_data	The id of the stylesheet you wish to cache
 */
function update_stylesheet($submitted_data) {
	
	// Bring wpdb into scope
	global $wpdb;
	
	// Generate the table name
	$table_name = $wpdb->prefix . "jgcss_stylesheets";
	
	// Structure the update data
	$update_data = array(
		'stylesheet_name' => $submitted_data['name'],
		'stylesheet_content' => $submitted_data['content'],
		'stylesheet_modified' => current_time( 'mysql' )
	);
	
	// UPDATE WHERE clause data
	$update_where = array(
		'stylesheet_id' => $submitted_data['stylesheet_id']
	);

	// Update the stylesheet table with the file location
	$wpdb->update( $table_name, $update_data, $update_where );
	
	// Update the stylesheets cache file
	cache_stylesheet($submitted_data['stylesheet_id']);
	
}

/**
 * Caches a stylesheet from the database into a physical file
 * @param int $stylesheet_id	The id of the stylesheet you wish to cache
 */
function cache_stylesheet($stylesheet_id) {
	
	// Bring wpdb into scope
	global $wpdb;
	
	// Generate the table name
	$table_name = $wpdb->prefix . "jgcss_stylesheets";

	// Build a query to grab the stylesheet data
	$query = "
		SELECT *
		FROM `$table_name`
		WHERE `stylesheet_id` = $stylesheet_id";
	
	// Get the stylesheet data
	$stylesheet_data = $wpdb->get_row($query);
	
	// Get the author information
	$user_data = get_userdata($stylesheet_data->stylesheet_author);
	
	// Generate a filename
	$filename_pieces = array(
		$stylesheet_data->stylesheet_name,
		$user_data->display_name,
		time()
	);
	$filename = md5(implode("-", $filename_pieces)) . '.css';
	
	// Check if a cached version exists. if it does, delete it
	if($stylesheet_data->stylesheet_file_path != "") {
		$file_path = ABSPATH . $stylesheet_data->stylesheet_file_path;
		((unlink($file_path)));
	}
	
	// Save the file and update the path in the database
	$upload_results = wp_upload_bits( $filename, null, stripslashes($stylesheet_data->stylesheet_content) );
	
	// Strip the website from the path and url
	$upload_path = str_replace(ABSPATH, '', $upload_results['file']);
	
	// Correct the stylesheet name if it was left blank
	if($stylesheet_data->stylesheet_name == 'Unnamed Stylesheet') {
		$stylesheet_name = 'Unnamed Stylesheet ' . $stylesheet_id;
	} else {
		$stylesheet_name = $stylesheet_data->stylesheet_name;
	}
	
	// Structure the update data
	$update_data = array(
		'stylesheet_name' => $stylesheet_name,
		'stylesheet_file_path' => $upload_path,
	);
	
	// UPDATE WHERE clause data
	$update_where = array(
		'stylesheet_id' => $stylesheet_data->stylesheet_id
	);
	
	// Update the stylesheet table with the file location
	$wpdb->update( $table_name, $update_data, $update_where );
	
}

/**
 * Deletes a stylesheet
 * @param array $stylesheet_id	The id of the stylesheet you wish to cache
 */
function delete_stylesheet($stylesheet_id) {
	
	// Bring wpdb into scope
	global $wpdb;
	
	// Generate the table name
	$table_name = $wpdb->prefix . "jgcss_stylesheets";
	
	// Build a query to delete the stylesheet
	$delete_query = "
		DELETE FROM `$table_name`
		WHERE `stylesheet_id` = $stylesheet_id
		LIMIT 1";
	
	// Delete the cached stylesheet file
	delete_cache_stylesheet($stylesheet_id);

	// Run the query
	$wpdb->query($delete_query);
	
}

/**
 * Deletes a cached stylesheet file
 * @param array $stylesheet_id	The id of the stylesheet you wish to cache
 */
function delete_cache_stylesheet($stylesheet_id) {
	
	// Bring wpdb into scope
	global $wpdb;
	
	// Generate the table name
	$table_name = $wpdb->prefix . "jgcss_stylesheets";

	// Build a query to grab the stylesheet data
	$query = "
		SELECT *
		FROM `$table_name`
		WHERE `stylesheet_id` = $stylesheet_id";
	
	// Get the stylesheet data
	$stylesheet_data = $wpdb->get_row($query);
	
	// Delete the file
	$file_path = substr(getcwd(), 0, -8) . $stylesheet_data->stylesheet_file_path;
	unlink($file_path);
	
}

?>
