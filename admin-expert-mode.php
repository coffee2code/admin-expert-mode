<?php
/**
 * Plugin Name: Admin Expert Mode
 * Version:     2.9
 * Plugin URI:  https://coffee2code.com/wp-plugins/admin-expert-mode/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: admin-expert-mode
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Allows users to hide inline documentation and help text that are geared for beginning users in the WordPress admin.
 *
 * Compatible with WordPress 2.8 through 5.8+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/admin-expert-mode/
 *
 * @package Admin_Expert_Mode
 * @author  Scott Reilly
 * @version 2.9
 */

/*
	Copyright (c) 2009-2021 by Scott Reilly (aka coffee2code)

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

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_AdminExpertMode' ) ) :

class c2c_AdminExpertMode {

	/**
	 * The name for the admin option.
	 *
	 * @access private
	 * @var string
	 */
	private static $admin_options_name = 'c2c_admin_expert_mode';

	/**
	 * Name for the admin field.
	 *
	 * @access private
	 * @var string
	 */
	private static $field_name         = 'admin_expert_mode';

	/**
	 * Translated prompt/label for the admin field.
	 *
	 * Note: This is set during `init()`.
	 *
	 * @access private
	 * @var string
	 */
	private static $prompt             = '';

	/**
	 * Translated help text for the admin field.
	 *
	 * Note: This is set during `init()`.
	 *
	 * @access private
	 * @var string
	 */
	private static $help_text          = '';

	/**
	 * Memoized plugin configuration.
	 *
	 * @access private
	 * @var array
	 */
	private static $options            = array();

	/**
	 * Name of the query key used for disabling expert mode for current page.
	 *
	 * @since 2.4
	 * @var string
	 */
	private static $disable_query_key  = 'disable-admin-expert-mode';


	/**
	 * Returns version of the plugin.
	 *
	 * @access public
	 * @since 1.8
	 */
	public static function version() {
		return '2.9';
	}

	/**
	 * Initializes plugin.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public static function init() {
		// Fire off a function when the plugin gets activated.
		register_activation_hook( __FILE__, array( __CLASS__, 'plugin_activated' ) );

		if ( is_admin() ) {
			// Load textdomain.
			load_plugin_textdomain( 'admin-expert-mode' );

			// Set translatable strings.
			self::$prompt =    __( 'Expert mode', 'admin-expert-mode' );
			self::$help_text = __( 'Enable expert mode (disables most of the onscreen documentation in the admin)', 'admin-expert-mode' );
		}

		// Register and enqueue styles for admin page.
		add_action( 'admin_init',               array( __CLASS__, 'register_styles'           ) );

		// Display admin notice on same page load as plugin activation.
		add_action( 'admin_notices',            array( __CLASS__, 'display_activation_notice' ) );

		// Add the checkbox to user profiles.
		add_action( 'personal_options',         array( __CLASS__, 'show_option'               ) );

		// Save the user preference.
		add_action( 'personal_options_update',  array( __CLASS__, 'maybe_save_options'        ) );
		add_action( 'edit_user_profile_update', array( __CLASS__, 'maybe_save_options'        ) );

		// Enqueue admin scripts and styles.
		add_action( 'admin_enqueue_scripts',    array( __CLASS__, 'enqueue_admin_css'         ) );
	}

	/**
	 * Resets plugin to pre-pageload state.
	 *
	 * Note: This mostly clears runtime caches. It does not revert the plugin to
	 * its freshly installed state.
	 *
	 * @access public
	 * @since 2.6
	 */
	public static function reset() {
		self::$options = array();
	}

	/**
	 * Sets a temporary flag (transient) to indicate the plugin was just activated.
	 *
	 * @access public
	 * @since 1.5.0
	 */
	public static function plugin_activated() {
		set_transient( 'aem_activated', 'show', 10 );
	}

	/**
	 * Outputs activation notice.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public static function display_activation_notice() {
		if ( get_transient( 'aem_activated' ) ) {
			if ( self::is_admin_expert_mode_active() ) {
				/* translators: %s: URL to user's profile page in the admin. */
				$msg = __( 'Expert mode is now enabled for you since you had it previously enabled. You can disable it in your <a href="%s" title="Profile">profile</a>. Reminder: other admins must separately enable expert mode for themselves via their own profiles. (See the readme.txt for more advanced controls.)', 'admin-expert-mode' );
			} else {
				/* translators: %s: URL to user's profile page in the admin. */
				$msg = __( '<strong>NOTE:</strong> You must enable expert mode for yourself (in your <a href="%s" title="Profile">profile</a>) for it to take effect. Other admin users must do the same for themselves as well. (See the readme.txt for more advanced controls.)', 'admin-expert-mode' );
			}
			$msg = sprintf( $msg, esc_url( admin_url( 'profile.php' ) ) );

			echo "<div id='message' class='updated fade'><p>$msg</p></div>\n";
		}
	}

	/**
	 * Indicates if admin expert mode is active for the current user.
	 *
	 * Takes the following into account in this order:
	 * - Value of 'c2c_admin_expert_mode' filter, if true
	 * - Value of the per-user setting
	 *
	 * @access public
	 * @since 1.5.0
	 *
	 * @return bool True is admin expert mode is active, else false.
	 */
	public static function is_admin_expert_mode_active() {
		$options = self::get_options();

		if ( ! empty( $_GET[ self::$disable_query_key ] ) ) {
			return false;
		}

		/**
		 * Filters whether the admin expert mode should be active for the current
		 * user.
		 *
		 * @since 1.5.0
		 *
		 * @param bool   $is_active  Is admin expert mode currently enabled for user?
		 * @param string $user_login Login of the current user.
		 */
		return (bool) apply_filters( 'c2c_admin_expert_mode', $options[ self::$field_name ], get_user_option( 'user_login' ) );
	}

	/**
	 * Registers styles.
	 *
	 * @access public
	 * @since 1.8
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__ . '_admin', plugins_url( 'admin.css', __FILE__ ) );
	}

	/**
	 * Enqueues stylesheets if the user has admin expert mode activated.
	 *
	 * @access public
	 * @since 1.8
	 */
	public static function enqueue_admin_css() {
		if ( self::is_admin_expert_mode_active() ) {
			wp_enqueue_style( __CLASS__ . '_admin' );
		}
	}

	/**
	 * Outputs the form input field for the admin expert mode setting checkbox.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @param WP_User $user The user whose options are being shown.
	 */
	public static function show_option( $user ) {
		$current_user = wp_get_current_user();
		$is_current_user_profile_page = ( $user->ID === $current_user->ID );

		// Only show on current user's own profile, or other user profiles if current
		// user has appropriate capabilities.
		if ( ! $is_current_user_profile_page && ! current_user_can( 'edit_users' ) ) {
			return;
		}

		$options = self::get_options( $user->ID );

		echo '<tr><th scope="row">' . self::$prompt . '</th>';
		echo '<td>';
		printf(
			'<label for="%s"><input type="checkbox" id="%s" name="%s" value="%s"%s>',
			esc_attr( self::$field_name ),
			esc_attr( self::$field_name ),
			esc_attr( self::$field_name ),
			'1',
			checked( (bool) $options[ self::$field_name ], true, false )
		);
		echo self::$help_text;
		echo "</label></td></tr>\n";
	}

	/**
	 * Returns array of the plugin's settings.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @param int $user_id The user ID. Default is ID of current user.
	 * @return array The plugin's settings
	 */
	public static function get_options( $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = wp_get_current_user()->ID;
		}

		if ( ! empty( self::$options[ $user_id ] ) ) {
			return self::$options[ $user_id ];
		}

		$user_setting_value = get_user_option( self::$admin_options_name, $user_id );

		/**
		 * Filters whether the admin expert mode should be active by default.
		 *
		 * Note: This only affects the default value and does not override what the
		 * user has set themselves. To override the value of the user setting (and
		 * thus have the final say in whether the mode is enabled or not, use the
		 * `c2c_admin_expert_mode` filter.)
		 *
		 * @since 1.5.0
		 *
		 * @param bool $is_active Is admin expert mode active by default? Default false.
		 * @param int  $user_id   The user ID.
		 */
		$default = (bool) apply_filters( 'c2c_admin_expert_mode_default', false, $user_id );
		self::$options[ $user_id ][ self::$field_name ] = $user_setting_value || $default;

		return self::$options[ $user_id ];
	}

	/**
	 * Saves value of checkbox to allow user to opt into receiving
	 * notifications for all comments.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @param int  $user_id The user ID.
	 */
	public static function maybe_save_options( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$options = self::get_options( $user_id );
		$options[ self::$field_name ] = ! empty( $_POST[ self::$field_name ] );

		if ( empty( $_POST[ self::$field_name ] ) ) {
			delete_user_option( $user_id, self::$admin_options_name );
		} else {
			update_user_option( $user_id, self::$admin_options_name, $options );
		}

		self::$options[ $user_id ] = $options;
	}

} // end c2c_AdminExpertMode

add_action( 'plugins_loaded', array( 'c2c_AdminExpertMode', 'init' ) );

endif; // end if !class_exists()
