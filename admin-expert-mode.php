<?php
/**
 * @package Admin_Expert_Mode
 * @author Scott Reilly
 * @version 1.8.1
 */
/*
Plugin Name: Admin Expert Mode
Version: 1.8.1
Plugin URI: http://coffee2code.com/wp-plugins/admin-expert-mode/
Author: Scott Reilly
Author URI: http://coffee2code.com/
Text Domain: admin-expert-mode
Domain Path: /lang/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Description: Allow users with access to the administration section to hide inline documentation and help text, which generally target beginning users.

Compatible with WordPress 2.8 through 3.4+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/admin-expert-mode/
*/

/*
	Copyright (c) 2009-2012 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( is_admin() && ! class_exists( 'c2c_AdminExpertMode' ) ) :

class c2c_AdminExpertMode {
	private static $admin_options_name = 'c2c_admin_expert_mode';
	private static $field_name         = 'admin_expert_mode';
	private static $prompt             = '';
	private static $help_text          = '';
	private static $config             = array();
	private static $options            = array();
	private static $activating         = false;
	private static $is_active          = false; // Has admin expert mode been determined to be active?

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.8
	 */
	public static function version() {
		return '1.8.1';
	}

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
		load_plugin_textdomain( 'admin-expert-mode', false, basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' );

		self::$prompt =    __( 'Expert mode', 'admin-expert-mode' );
		self::$help_text = __( 'Enable expert mode (if you are familiar with WordPress and don\'t need the inline documentation in the admin).', 'admin-expert-mode' );

		add_action( 'admin_notices',         array( __CLASS__, 'display_activation_notice' ) );
		add_action( 'load-profile.php',      array( __CLASS__, 'register_profile_page_hooks' ) );
		// Register and enqueue styles for admin page
		self::register_styles();
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_css' ) );
	}

	/**
	 * Filters/actions to hook on the admin profile.php page.
	 *
	 */
	public static function register_profile_page_hooks() {
		self::maybe_save_options();
		add_action( 'profile_personal_options', array( __CLASS__, 'show_option' ) );
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
			if ( self::is_admin_expert_mode_active() )
				$msg = __( 'Expert mode is now enabled for you since you had it previously enabled. You can disable it in your <a href="%s" title="Profile">profile</a>. Reminder: other admins must separately enable expert mode for themselves via their own profiles. (See the readme.txt for more advanced controls.)', 'admin-expert-mode' );
			else
				$msg = __( '<strong>NOTE:</strong> You must enable expert mode for yourself (in your <a href="%s" title="Profile">profile</a>) for it to take effect. Other admin users must do the same for themselves as well. (See the readme.txt for more advanced controls.)', 'admin-expert-mode' );
			$msg = sprintf( $msg, admin_url( 'profile.php' ) );
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
	 * Registers styles.
	 *
	 * @since 1.8
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__ . '_admin', plugins_url( 'admin.css', __FILE__ ) );
	}

	/**
	 * Enqueues stylesheets if the user has admin expert mode activated.
	 *
	 * @since 1.8
	 */
	public static function enqueue_admin_css() {
		if ( self::is_admin_expert_mode_active() )
			wp_enqueue_style( __CLASS__ . '_admin' );
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
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'update' ) {
			$options = self::get_options();
			$options[self::$field_name] = $_POST[self::$field_name] ? 1 : 0;
			update_user_option( $user->ID, self::$admin_options_name, $options );
			self::$options = $options;
		}
	}

} // end c2c_AdminExpertMode

c2c_AdminExpertMode::init();

endif; // end if !class_exists()
