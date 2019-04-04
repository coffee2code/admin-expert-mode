<?php
/**
 * Plugin Name: Admin Expert Mode
 * Version:     2.4
 * Plugin URI:  http://coffee2code.com/wp-plugins/admin-expert-mode/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: admin-expert-mode
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Alows users to hide inline documentation and help text that are geared for beginning users in the WordPress admin.
 *
 * Compatible with WordPress 2.8 through 5.1+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/admin-expert-mode/
 *
 * @package Admin_Expert_Mode
 * @author  Scott Reilly
 * @version 2.4
 */

/*
 * TODO:
 * - Permit admins to see and edit the value of the setting for other users
 * - Add inline documentation for class variables
 *
 */

/*
	Copyright (c) 2009-2019 by Scott Reilly (aka coffee2code)

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

if ( is_admin() && ! class_exists( 'c2c_AdminExpertMode' ) ) :

class c2c_AdminExpertMode {
	private static $admin_options_name = 'c2c_admin_expert_mode';
	private static $field_name         = 'admin_expert_mode';
	private static $prompt             = '';
	private static $help_text          = '';
	private static $config             = array();
	private static $options            = array();
	private static $is_active          = false; // Has admin expert mode been determined to be active?

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
	 * @since 1.8
	 */
	public static function version() {
		return '2.4';
	}

	/**
	 * Initializer.
	 */
	public static function init() {
		// Fire off a function when the plugin gets activated.
		register_activation_hook( __FILE__, array( __CLASS__, 'plugin_activated' ) );

		// Load textdomain.
		load_plugin_textdomain( 'admin-expert-mode' );

		// Set translatable strings.
		self::$prompt =    __( 'Expert mode', 'admin-expert-mode' );
		self::$help_text = __( "Enable expert mode (if you are familiar with WordPress and don't need the inline documentation in the admin).", 'admin-expert-mode' );

		// Register and enqueue styles for admin page.
		add_action( 'init',                     array( __CLASS__, 'register_styles'           ) );

		// Register hooks.
		add_action( 'admin_notices',            array( __CLASS__, 'display_activation_notice' ) );
		add_action( 'profile_personal_options', array( __CLASS__, 'show_option'               ) );
		add_action( 'personal_options_update',  array( __CLASS__, 'maybe_save_options'        ) );
		add_action( 'admin_enqueue_scripts',    array( __CLASS__, 'enqueue_admin_css'         ) );
	}

	/**
	 * Set a temporary flag (transient) to indicate the plugin was just activated.
	 */
	public static function plugin_activated() {
		set_transient( 'aem_activated', 'show', 10 );
	}

	/**
	 * Outputs activation notice.
	 */
	public static function display_activation_notice() {
		if ( get_transient( 'aem_activated' ) ) {
			if ( self::is_admin_expert_mode_active() ) {
				$msg = __( 'Expert mode is now enabled for you since you had it previously enabled. You can disable it in your <a href="%s" title="Profile">profile</a>. Reminder: other admins must separately enable expert mode for themselves via their own profiles. (See the readme.txt for more advanced controls.)', 'admin-expert-mode' );
			} else {
				$msg = __( '<strong>NOTE:</strong> You must enable expert mode for yourself (in your <a href="%s" title="Profile">profile</a>) for it to take effect. Other admin users must do the same for themselves as well. (See the readme.txt for more advanced controls.)', 'admin-expert-mode' );
			}
			$msg = sprintf( $msg, esc_url( admin_url( 'profile.php' ) ) );

			echo "<div id='message' class='updated fade'><p>$msg</p></div>";
		}
	}

	/**
	 * Indicates if admin expert mode is active for the current user.
	 *
	 * Takes the following into account in this order:
	 * * Value of 'c2c_admin_expert_mode' filter, if true
	 * * Value of the per-user setting
	 */
	public static function is_admin_expert_mode_active() {
		$options = self::get_options();

		if ( ! empty( $_GET[ self::$disable_query_key ] ) ) {
			return false;
		}

		/**
		 * Filteres whether the admin expert mode should be active for the current
		 * user.
		 *
		 * @since 1.5.0
		 *
		 * @param bool   $is_active  Is admin expert mode currently active?
		 * @param string $user_login Login of the current user.
		 */
		if ( self::$is_active || (bool) apply_filters( 'c2c_admin_expert_mode', $options[ self::$field_name ], get_user_option( 'user_login' ) ) ) {
			self::$is_active = true;
		}

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
		if ( self::is_admin_expert_mode_active() ) {
			wp_enqueue_style( __CLASS__ . '_admin' );
		}
	}

	/**
	 * Outputs the form input field for the admin expert mode setting checkbox.
	 */
	public static function show_option( $user ) {
		$options = self::get_options();

		echo '<table class="form-table"><tr><th scope="row">' . self::$prompt . '</th>';
		echo '<td>';
		printf(
			'<label for="%s"><input type="checkbox" id="%s" name="%s" value="%s"%s>' . "\n",
			esc_attr( self::$field_name ),
			esc_attr( self::$field_name ),
			esc_attr( self::$field_name ),
			'1',
			checked( (bool) $options[ self::$field_name ], true, false )
		);
		echo self::$help_text;
		echo '</label></td></tr></table>';
	}

	/**
	 * Returns array of the plugin's settings.
	 *
	 * @return array The plugin's settings
	 */
	public static function get_options() {
		if ( self::$options ) {
			return self::$options;
		}

		$existing_options = get_user_option( self::$admin_options_name );
		/**
		 * Filteres whether the admin expert mode should be active by default.
		 *
		 * @since 1.5.0
		 *
		 * @param bool $is_active Is admin expert mode active by default? Default false.
		 */
		$default          = (bool) apply_filters( 'c2c_admin_expert_mode_default', false );
		self::$options    = wp_parse_args( $existing_options, array( self::$field_name => $default ) );

		return self::$options;
	}

	/**
	 * Saves value of checkbox to allow user to opt into receiving
	 * notifications for all comments.
	 *
	 * @access public
	 *
	 * @param  int  $user_id The user ID.
	 */
	public static function maybe_save_options( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$options = self::get_options();
		$options[ self::$field_name ] = ! empty( $_POST[ self::$field_name ] );

		if ( empty( $_POST[ self::$field_name ] ) ) {
			delete_user_option( $user_id, self::$admin_options_name );
		} else {
			update_user_option( $user_id, self::$admin_options_name, $options );
		}

		self::$options = $options;
	}

} // end c2c_AdminExpertMode

add_action( 'plugins_loaded', array( 'c2c_AdminExpertMode', 'init' ) );

endif; // end if !class_exists()
