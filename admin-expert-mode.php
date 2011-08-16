<?php
/**
 * @package Admin_Expert_Mode
 * @author Scott Reilly
 * @version 1.7.2
 */
/*
Plugin Name: Admin Expert Mode
Version: 1.7.2
Plugin URI: http://coffee2code.com/wp-plugins/admin-expert-mode/
Author: Scott Reilly
Author URI: http://coffee2code.com
Text Domain: admin-expert-mode
Description: Allow users with access to the administration section to hide inline documentation and help text, which generally target beginning users.

Compatible with WordPress 2.8+, 2.9+, 3.0+, 3.1+, 3.2+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/admin-expert-mode/

TODO:
	* Change activation admin notice to recognize if settings is already true for user and say so.

*/

/*
Copyright (c) 2009-2011 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( is_admin() && ! class_exists( 'c2c_AdminExpertMode' ) ) :

class c2c_AdminExpertMode {
	private static $admin_options_name = 'c2c_admin_expert_mode';
	private static $field_name         = 'admin_expert_mode';
	private static $textdomain         = 'admin-expert-mode';
	private static $textdomain_subdir  = 'lang';
	private static $prompt             = '';
	private static $help_text          = '';
	private static $config             = array();
	private static $options            = array();
	private static $activating         = false;
	private static $is_active          = false; // Has admin expert mode been determined to be active?

	/**
	 * Constructor
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'do_init' ) );
		register_activation_hook( __FILE__, array( __CLASS__, 'plugin_activated' ) );
	}

	/**
	 * Perform initialization
	 */
	public static function do_init() {
		global $pagenow;
		self::load_textdomain();

		self::$prompt =    __( 'Expert mode', self::$textdomain );
		self::$help_text = __( 'Enable expert mode (if you are familiar with WordPress and don\'t need the inline documentation in the admin).', self::$textdomain );

		add_action( 'admin_head',    array( __CLASS__, 'add_css' ) );
		add_action( 'admin_notices', array( __CLASS__, 'display_activation_notice' ) );
		if ( 'profile.php' == $pagenow ) {
			add_action( 'admin_init',               array( __CLASS__, 'maybe_save_options' ) );
			add_action( 'profile_personal_options', array( __CLASS__, 'show_option' ) );
		}
	}

	/**
	 * Loads the localization textdomain for the plugin.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		$subdir = empty( self::$textdomain_subdir ) ? '' : ( '/' . self::$textdomain_subdir );
		load_plugin_textdomain( self::$textdomain, false, basename( dirname( __FILE__ ) ) . $subdir );
	}

	/**
	 * Set a temporary flag (transient) to indicate the plugin was just activated.
	 *
	 * @return void
	 */
	public static function plugin_activated() {
		set_transient( 'aem_activated', 'show', 10 );
	}

	/**
	 * Output activation notice
	 *
	 * @return void (Text is echoed.)
	 */
	public static function display_activation_notice() {
		if ( get_transient( 'aem_activated' ) ) {
			$msg = sprintf( __( '<strong>NOTE:</strong> You must enable expert mode for yourself (in your <a href="%s" title="Profile">profile</a>) for it to take effect. Other admin users must do the same for themselves as well. (See the readme.txt for more advanced controls.)', self::$textdomain ), admin_url( 'profile.php' ) );
			echo "<div id='message' class='updated fade'><p>$msg</p></div>";
		}
	}

	/**
	 * Indicates if admin expert mode is active for the current user.
	 *
	 * Takes the following into account in this order
	 * * Value of 'c2c_admin_expert_mode' filter, if true
	 * * Value of the per-user setting
	 *
	 * @return void (Text is echoed.)
	 */
	public static function is_admin_expert_mode_active() {
		$options = self::get_options();
		if ( self::$is_active || apply_filters( 'c2c_admin_expert_mode', $options[self::$field_name], get_user_option( 'user_login' ) ) )
			self::$is_active = true;
		return self::$is_active;
	}

	/**
	 * Outputs CSS if the user has admin expert mode activated.
	 *
	 * @return void (Text is echoed.)
	 */
	public static function add_css() {
		if ( ! self::is_admin_expert_mode_active() )
			return;

		echo <<<CSS
		<style type="text/css">
		#postexcerpt .inside > p, #trackbacksdiv .inside > p:last-child, #postcustom .inside > p, 
		#pagecustomdiv .inside > p, #pagecommentstatusdiv .inside > p:last-child, 
		#pageparentdiv .inside > select + p, #pageparentdiv .inside > p:last-child,
		#namediv .inside > p, #descriptiondiv .inside > p, #linktargetdiv .inside > p,
		#linkxfndiv .inside > p, #addressdiv .inside > p,
		#current-widgets-head #sidebar-info p:last-child,
		#upload-form label,
		.tools-php p.description,
		.options-general-php span.description,
		.options-permalink-php form p,
		#icon-plugins + h2 + p, #currently-active + form + p, #recent-plugins + p, #inactive-plugins + form + h2 + p + p + p,
		#addcat .form-field p, #addtag .form-field p, .edit-tags-php #col-right .form-wrap, .install-help { display:none; }
		.options-permalink-php form p { display:block; }
		</style>

CSS;
	}

	/**
	 * Outputs the form input field for the admin expert mode setting checkbox.
	 *
	 * @return void (Text is echoed.)
	 */
	public static function show_option( $user ) {
		$options = self::get_options();
		$checked = $options[self::$field_name] ? ' checked="checked"' : '';
		echo '<table class="form-table"><tr><th scope="row">' . self::$prompt . '</th>';
		echo '<td><label for="' . self::$field_name . '"><input type="checkbox" value="true" id="' . self::$field_name . '" name="' . self::$field_name . "\"{$checked}/>\n";
		echo self::$help_text . '</label></td></tr></table>';
	}

	/**
	 * Returns array of the plugin's settings.
	 *
	 * @return array The plugin's settings
	 */
	public static function get_options() {
		if ( ! empty( self::$options ) )
			return self::$options;
		$existing_options = get_user_option( self::$admin_options_name );
		$default = apply_filters( 'c2c_admin_expert_mode_default', false );
		self::$options = wp_parse_args( $existing_options, array( self::$field_name => $default ) );
		return self::$options;
	}

	/**
	 * Saves the user setting.
	 *
	 * @return void
	 */
	public static function maybe_save_options() {
		$user = wp_get_current_user();
		if ( isset( $_POST['submit'] ) ) {
			$options = self::get_options();
			$options[self::$field_name] = $_POST[self::$field_name] ? 1 : 0;
			update_user_option( $user->ID, self::$admin_options_name, $options );
			self::$options = $options;
		}
	}

} // end c2c_AdminExpertMode

c2c_AdminExpertMode::init();

endif; // end if !class_exists()

?>