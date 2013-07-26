<?php
/**
 * Plugin Name.
 *
 * @package   Search_Keyword_Redirect
 * @author    Nick Pelton <nick@werkpress.com>
 * @license   GPL-2.0+
 * @link      http://werkpress.com/plugins
 * @copyright 2013 Nick Pelton or Werkpress
 */

/**
 * Plugin class.
 *
 *
 * @package Search_Keyword_Redirect
 * @author  Nick Pelton <nick@werkpress.com>
 */
class Search_Keyword_Redirect {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1.0
	 *
	 * @var     string
	 */
	protected $version = '0.1.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'search-keyword-redirect';

	/**
	 * Instance of this class.
	 *
	 * @since    0.1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     0.1.0
	 */
	private function __construct() {



		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript. None in use... yet
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Init code
		add_action('template_redirect', array($this, 'search_keyword_redirect'));

		// If the save button was clicked (post var will exist). Also check nounce.
		if ( isset( $_POST['submit_keywords'] ) && check_admin_referer( 'ww_submit_save_form', 'ww_keyword_redirects_nonce' ) ) {

			$this->save_keyword_redirects($_POST['ww_keyword_redirects']);
		
		}

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    0.1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		

		$ww_keyword_redirects_options = array(); 
	
		update_option( 'ww_keyword_redirects', $ww_keyword_redirects_options );

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    0.1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// No deactivation code
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     0.1.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     0.1.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('underscore');
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.1.0
	 */
	public function add_plugin_admin_menu() {


		// $this->plugin_screen_hook_suffix = add_plugins_page(
		// 	__( 'Search Keyword Redirects', $this->plugin_slug ),
		// 	__( 'Keyword Redirects', $this->plugin_slug ),
		// 	'read',
		// 	$this->plugin_slug,
		// 	array( $this, 'display_plugin_admin_page' )
		// );

		$this->plugin_screen_hook_suffix = add_menu_page( _('Search Keywords'), _("Search Keywords"), 'manage_options', $this->plugin_slug, array($this,'display_plugin_admin_page'), plugins_url( 'assets/img/icon.png', __FILE__ ) , 30 );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    0.1.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Attempt to match keywords to search query and redirect on match
	 *
	 * @since    0.1.0
	 */
	public function search_keyword_redirect(){
			
		if(!is_search()) return; // not a search
				
			// get options
			$redirects = get_option('ww_keyword_redirects');
			
			// check if options exist
			if (!empty($redirects)) {
			
				// loop through each keyword
				foreach ($redirects as $keyword => $redirect) {
						
						// check if keyword exists in search query
						if (preg_match("/$keyword/i", get_search_query()) == TRUE){ 

								// redirect to url
								wp_redirect($redirect[0]);

								exit(); // my work is done here
								
						}
								
				} // end foreach
				
				// no keywords matched
				unset($redirects);
				return;
			
			}else{

				return; // no redirects
			
			}

	} // END search_keyword_redirect()

	/**
	 * Save codes on form submission
	 *
	 * @since    0.1.0
	 */
	public function save_keyword_redirects($data)
		{
			

			$redirects = array();
			
			for($i = 0; $i < sizeof($data['request']); ++$i) {
				$request = trim($data['request'][$i]);
				$destination = trim($data['destination'][$i]);
			
				if ($request == '' && $destination == '') { continue; }
				else { 
				
					$redirects[$request] = array($destination,$date,$max,$used);
										
				}
			}

			update_option('ww_keyword_redirects', $redirects);
		}


	/**
	 * Render existing keyword redirects
	 *
	 * @since    0.1.0
	 */
	private function get_keyword_redirects(){
			$redirects = get_option('ww_keyword_redirects');
			$output = '';
			if (!empty($redirects)) {
				foreach ($redirects as $request => $data) {
					$output .= '
					
					<tr>
						<td><input type="text" name="ww_keyword_redirects[request][]" value="'.$request.'" style="width:15em" />&nbsp;&raquo;&nbsp;</td>
						<td><input type="text" name="ww_keyword_redirects[destination][]" value="'.$data[0].'" style="width:30em;" /><!--&nbsp;&raquo;&nbsp;--></td>
						<!-- <td><input type="text" name="ww_keyword_redirects[used][]" value="'.$data[1].'" style="width:10em;" readonly/></td> -->
						<td><a href="#" class="delete_redirect button" title="Delete Redirect">'._('Delete Redirect').'<a/></td>
					</tr>
					
					';
				}
			}else{
				$output .= '
					
					<tr>
						<td><input type="text" name="ww_keyword_redirects[request][]" value="" style="width:15em" />&nbsp;&raquo;&nbsp;</td>
						<td><input type="text" name="ww_keyword_redirects[destination][]" value="" style="width:30em;" /><!--&nbsp;&raquo;&nbsp;--></td>
						<!-- <td><input type="text" name="ww_keyword_redirects[used][]" value="" style="width:10em;" readonly/></td> -->
						<td><a href="#" class="delete_redirect button" title="Delete Redirect">'._('Delete Redirect').'<a/></td>
					</tr>';
			}
			return $output;
		}

	public function display_error_notice() {
   
    	
    	return "<div class='error'><p>".__( 'An error has occurred!', 'my-text-domain' )."</p></div>";
    
	}

} // END CLASS