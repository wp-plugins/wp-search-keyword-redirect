<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Search_Keyword_Redirect
 * @author    Nick Pelton <nick@werkpress.com>
 * @license   GPL-2.0+
 * @link      http://werkpress.com/plugin
 * @copyright 2013 Nick Pelton or Werkpress
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete data from options table 
delete_option( 'ww_keyword_redirects' ); // remove