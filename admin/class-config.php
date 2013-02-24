<?php
/**
 * @package Admin
 */

/**
 * class JGCSS_Admin_Pages
 *
 * Class with functionality for the WP SEO admin pages.
 */
class JGCSS_Admin_Pages {

	/**
	 * @var string $currentoption The option in use for the current admin page.
	 */
	var $currentoption = 'jgcss';

	/**
	 * @var array $adminpages Array of admin pages that the plugin uses.
	 */
	var $adminpages = array(
		'jgcss_dashboard',
		'jgcss_new_stylesheet',
		'jgcss_edit_stylesheet'
	);

	/**
	 * Class constructor, which basically only hooks the init function on the init hook
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ), 20 );
	}

	/**
	 * Make sure the needed scripts are loaded for admin pages
	 */
	function init() {
	}

	/**
	 * Generates the header for admin pages
	 *
	 * @param string $title				The title to show in the main heading.
	 * @param array $header_button		An array including two keys - label and url
	 * @param array $form				An array including four keys - name, action, method and id
	 */
	function admin_header( $title, $header_button = false, $form = false ) {
	?>
		<div class="wrap">
            <div id="joddit-icon" style="background: url('<?php echo JGCSS_URL; ?>images/jwp-32x32.png') no-repeat;" class="icon32"><br /></div>
            <h2 id="jgcss-title">Joddit Global CSS: <?php echo $title; ?><?php if(is_array($header_button)) { ?> <a href="<?php echo $header_button['url'] ?>" class="add-new-h2"><?php echo $header_button['label'] ?></a><?php } ?></h2>
    		<?php if($_GET['message'] == 'created') { ?>
			<div id="message" class="updated below-h2"><p>Your new stylesheet has been created and saved successfully.</p></div>
            <?php } else if($_GET['message'] == 'updated') { ?>
			<div id="message" class="updated below-h2"><p>Your stylesheet has been updated and saved successfully.</p></div>
            <?php } // end if ?>
            <div id="jgcss_content_top">
                <div class="metabox-holder">	
                    <div class="meta-box-sortables">
						<?php if(is_array($form)) { ?><form name="<?php echo $form['name']; ?>" action="<?php echo $form['action']; ?>" method="<?php echo $form['method']; ?>" id="<?php echo $form['id']; ?>"><?php } ?>
	<?php

	}

	/**
	 * Generates the footer for admin pages
	 *
	 * @param bool $submit Whether or not a submit button should be shown.
	 */
	function admin_footer($form = false) {
	?>
							<?php if($form) { ?><div class="submit"><input type="submit" class="button-primary" name="submit" value="<?php echo $form['button_label']; ?>"/></div>
                        </form><?php } ?>
                    </div>
                </div>
            </div>
        </div>
	<?php				
	}

} // end class JGCSS_Admin
global $jgcss_admin_pages;
$jgcss_admin_pages = new JGCSS_Admin_Pages();
