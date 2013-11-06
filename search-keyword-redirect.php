<?php
/**
 * Search Keyword Redirect Plugin
 *
 * 
 *
 * @package   Search_Keyword_Redirect
 * @author    Nick Pelton <nick@werkpress.com>
 * @license   GPL-2.0+
 * @link      http://werkpress.com/plugin
 * @copyright 2013 Nick Pelton & Werkpress
 *
 * @wordpress-plugin
 * Plugin Name: Search Keyword Redirect
 * Plugin URI:  http://www.werkpress.com/plugins
 * Description: Matches search queries and to keywords. On match redirect to specific pages.
 * Version:     0.2.0
 * Author:      Nick Pelton
 * Author URI:  http://werkpress.com/plugins
 * Text Domain: search-keyword-redirect-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// require core class
require_once( plugin_dir_path( __FILE__ ) . 'class-search-keyword-redirect.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Search_Keyword_Redirect', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Search_Keyword_Redirect', 'deactivate' ) );

// Init
function ww_keyword_redirect_init(){
	Search_Keyword_Redirect::get_instance();
}

// Bind to init action
add_action('init','ww_keyword_redirect_init');