<?php
/**
 * @package Admin_Expert_Mode
 * @author Scott Reilly
 * @version 1.5
 */
/*
Plugin Name: Admin Expert Mode
Version: 1.5
Plugin URI: http://coffee2code.com/wp-plugins/admin-expert-mode/
Author: Scott Reilly
Author URI: http://coffee2code.com
Text Domain: admin-expert-mode
Description: Allow users with access to the administration section to hide inline documentation and help text, which generally target beginning users.

Compatible with WordPress 2.8+, 2.9+, 3.0+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/admin-expert-mode/
*/

/*
Copyright (c) 2009-2010 by Scott Reilly (aka coffee2code)

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

if ( is_admin() && !class_exists( 'AdminExpertMode' ) ) :

class AdminExpertMode {
	var $admin_options_name = 'c2c_admin_expert_mode';
	var $field_name = 'admin_expert_mode';
	var $textdomain = 'admin-expert-mode';
	var $textdomain_subdir = 'lang';
	var $prompt = '';
	var $help_text = '';
	var $config = array();
	var $activating = false;
	var $is_active = false; // Has admin expert mode been determined to be active?

	/**
	 * Constructor
	 */
	function AdminExpertMode() {
		global $pagenow;

		$this->prompt = __( 'Expert mode', $this->textdomain );
		$this->help_text = __( 'Enable expert mode if you are familiar with WordPress and don\'t need the inline documentation in the admin.', $this->textdomain );

		add_action( 'admin_head', array( &$this, 'add_css' ) );
		add_action( 'admin_notices', array( &$this, 'display_activation_notice' ) );
		add_action( 'activate_'.str_replace( trailingslashit( dirname( dirname( __FILE__ ) ) ), '', __FILE__ ), array( &$this, 'plugin_activated' ) );
		add_action( 'init', array( &$this, 'load_textdomain' ) );
		if ( 'profile.php' == $pagenow ) {
			add_action( 'admin_init', array( &$this, 'maybe_save_options' ) );
			add_action( 'profile_personal_options', array( &$this, 'show_option' ) );
		}
	}

	/**
	 * Loads the localization textdomain for the plugin.
	 *
	 * @return void
	 */
	function load_textdomain() {
		$subdir = empty( $this->textdomain_subdir ) ? '' : '/'.$this->textdomain_subdir;
		load_plugin_textdomain( $this->textdomain, false, basename( dirname( __FILE__ ) ) . $subdir );
	}

	/**
	 * Set a temporary flag (transient) to indicate the plugin was just activated.
	 *
	 * @return void
	 */
	function plugin_activated() {
		set_transient( 'aem_activated', 'show', 10 );
	}

	/**
	 * Output activation notice
	 *
	 * @return void (Text is echoed.)
	 */
	function display_activation_notice() {
		if ( get_transient( 'aem_activated' ) ) {
			$msg = sprintf( __( '<strong>NOTE:</strong> You must enable expert mode for yourself (in your <a href="%s" title="Profile">profile</a>) for it to take effect. Other admin users must do the same for themselves as well. (See the readme.txt for more advanced controls.)', $this->textdomain ), admin_url( 'profile.php' ) );
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
	function is_admin_expert_mode_active() {
		$options = $this->get_options();
		if ( $this->is_active || apply_filters( 'c2c_admin_expert_mode', $options[$this->field_name], get_user_option( 'user_login' ) ) )
			$this->is_active = true;
		return $this->is_active;
	}

	/**
	 * Outputs CSS if the user has admin expert mode activated.
	 *
	 * @return void (Text is echoed.)
	 */
	function add_css() {
		if ( !$this->is_admin_expert_mode_active() )
			return;

		echo <<<CSS
		<style type="text/css">
		#postexcerpt .inside > p, #trackbacksdiv .inside > p:last-child, #postcustom .inside > p, 
		#pagecustomdiv .inside > p, #pagecommentstatusdiv .inside > p:last-child, 
		#pageparentdiv .inside > select + p, #pageparentdiv .inside > p:last-child,
		#namediv .inside > p, #descriptiondiv .inside > p, #linktargetdiv .inside > p,
		#linkxfndiv .inside > p, #addressdiv .inside > p,
		#current-widgets-head #sidebar-info p:last-child,
		#icon-plugins + h2 + p, #currently-active + form + p, #recent-plugins + p, #inactive-plugins + form + h2 + p + p + p,
		#addcat .form-field p, #addtag .form-field p, .edit-tags-php #col-right .form-wrap, .install-help { display:none; }
		</style>

CSS;
	}

	/**
	 * Outputs the form input field for the admin expert mode setting checkbox.
	 *
	 * @return void (Text is echoed.)
	 */
	function show_option( $user ) {
		$options = $this->get_options();
		$checked = $options[$this->field_name] ? ' checked="checked"' : '';
		echo <<<HTML
		<table class="form-table">
			<tr>
			<th scope="row">{$this->prompt}</th>
			<td><label for="{$this->field_name}"><input type="checkbox" value="true" id="{$this->field_name}" name="{$this->field_name}"{$checked}/> {$this->help_text}</label></td>
			</tr>
		</table>

HTML;
	}

	/**
	 * Returns array of the plugin's settings.
	 *
	 * @return array The plugin's settings
	 */
	function get_options() {
		if ( !empty( $this->options ) )
			return $this->options;
		$existing_options = get_user_option( $this->admin_options_name );
		$default = apply_filters( 'c2c_admin_expert_mode_default', false );
		$this->options = wp_parse_args( $existing_options, array( $this->field_name => $default ) );
		return $this->options;
	}

	/**
	 * Saves the user setting.
	 *
	 * @return void
	 */
	function maybe_save_options() {
		$user = wp_get_current_user();
		if ( isset( $_POST['submit'] ) ) {
			$options = $this->get_options();
			$options[$this->field_name] = $_POST[$this->field_name] ? 1 : 0;
			update_user_option( $user->ID, $this->admin_options_name, $options );
			$this->options = $options;
		}
	}

} // end AdminExpertMode

$GLOBALS['c2c_admin_expert_mode'] = new AdminExpertMode();

endif; // end if !class_exists()

?>