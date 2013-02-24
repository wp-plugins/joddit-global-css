=== Global CSS by Joddit ===
Contributors: Joddit Web Services
Donate link: http://www.joddit.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: ultimatum, global stylesheet, css, global css, single stylesheet, single css
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 0.8.0

Simple Custom CSS in WordPress: Create and manage custom stylesheets with a powerful CSS editor based on the CodeMirror JavaScript component.

== Description ==

This plugin allows WordPress admins the ability to add and organize custom stylesheets within a friendly, familiar interface. The plugin also brings in the functionality of the CodeMirror JavaScript syntax highlighter component. This allows you to see your CSS in easily recognizable color patterns that will help you catch mistakes as you write.

== Screenshots ==

1. The plugin's main dashboard.
2. The individual stylesheet editor using CodeMirror for line counts, coloring and general syntax styling.

== Installation ==

1. Upload the `joddit-global-css` folder to the `/wp-content/plugins/` directory
2. Activate the Joddit Global Stylesheet plugin through the 'Plugins' menu in WordPress
3. Click on the new Global CSS menu item and create a stylesheet.

== Frequently Asked Questions ==

= Can I change the color scheme in the CSS editor? =

You can only change the color scheme of the CSS editor by modifying the core plugin code. I will be building a settings page where you will be able to do this in future releases.

== Changelog ==

= 0.6.8 =

* Initial public release.

= 0.7.1 =

* Cleaned up some unnecessary lib files and directories to reduce the plugin size.

= 0.7.2 =

* Added a few screenshots for the plugin page.

= 0.7.3 =

* Added a main splash banner to the plugin page.

= 0.7.5 =

* Fixed a bug when using the plugin with WordPress sites that are running from folders other than the web root such as http://example.com/blog.

= 0.7.6 =

* Fixed a small bug whereby the enqueued stylesheet url is missing a front slash.

= 0.7.7 =

* Fixed a small issue regarding stylesheet content and escaping characters.

= 0.7.8 =

* Fixed a bug where the javascript validation used in the plugin was interfering with other scripts.

= 0.8.0 =

* Fixed a bug where the placeholder text in the stylesheet title text field wouldn't disappear when clicked on.
* Changed the way the save stylesheet button works. It no longer redirects the user back to the stylesheet dashboard. Instead, the save button simply saves the stylesheet and reloads the stylesheet so the user can continue editing. This was a suggestion from the community that we thought made good sense since CSS is an iterative process.

== Upgrade Notice ==

= We highly recommend updating from 0.7.8 to avoid a JavaScript interface bug.  =
