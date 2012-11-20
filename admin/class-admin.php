<?php
/**
 * @package Admin
 */
 
 /**
 * Class that holds most of the admin functionality for Joddit Global CSS
 */
class JGCSS_Admin {

	/**
	 * Class constructor
	 */
	function __construct() {
		if ( $this->grant_access() ) {
			add_action( 'admin_menu', array( $this, 'register_dashboard_page' ) );
		}
	}

	/**
	 * Check whether the current user is allowed to access the configuration.
	 *
	 * @return boolean
	 */
	function grant_access() {
		return true;
	}

	/**
	 * Register the menu item
	 *
	 */
	function register_dashboard_page() {
		add_menu_page( 'Joddit Global CSS', 'Global CSS', 'manage_options', 'jgcss_dashboard', array( $this, 'dashboard_page'), JGCSS_URL . 'images/css-16x16.png' );
		add_submenu_page( 'jgcss_dashboard', 'Dashboard', 'All Stylesheets', 'manage_options', 'jgcss_dashboard', array($this, 'dashboard_page') );
		add_submenu_page( 'jgcss_dashboard', 'Add New Stylesheet', 'Add new', 'manage_options', 'jgcss_stylesheet', array($this, 'stylesheet_page') );
	}

	/**
	 * Loads the content for the Dashboard page.
	 */
	function dashboard_page() {
		if ( isset( $_GET['page'] ) && 'jgcss_dashboard' == $_GET['page'] )
			include( JGCSS_PATH . '/admin/pages/dashboard.php' );
	}

	/**
	 * Loads the content for the New Stylesheet page.
	 */
	function stylesheet_page() {
		if ( isset( $_GET['page'] ) && 'jgcss_stylesheet' == $_GET['page'] )
			include( JGCSS_PATH . '/admin/pages/stylesheet.php' );
	}

}

// Globalize the var first as it's needed globally.
global $jgcss_admin;
$jgcss_admin = new JGCSS_Admin();	

?>