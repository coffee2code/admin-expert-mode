<?php
/*
Plugin Name: Admin Expert Mode
Version: 1.2
Plugin URI: http://coffee2code.com/wp-plugins/admin-expert-mode
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Allow users with access to the adminstration section to hide inline documentation and help text, which generally target beginning users.

WordPress 2.7 introduced a variety of inline documentation that provide explanations for various feature and input fields 
(i.e. things like explanations of excerpts, trackbacks, custom fields, page parents, etc).  These are great for newcomers
to WordPress.  For those sufficiently familiar with WordPress, these bits of text are no longer necessary and merely
provide visual noise.  This plugin gets rid of those descriptive texts.

This plugin's behavior is made available as a per-user profile option.  Each user who wishes to enable expert mode for
themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the
'Update Profile' button.

Specifically, it removes:
	- Categories
		* Description of "Category Name"
		* Description of "Category Slug"
		* Description of "Category Parent"
		* Description of "Description"
	- Edit Post
		* Description of "Custom Fields"
		* Description of "Excerpts"
		* Description of "Trackbacks"
	- Edit Page
		* Description of comment status
		* Description of "Custom Fields"
		* Verbose descriptions of "Attributes" (parent, template, and order)
	- Edit Link
		* Description of "Name"
		* Description of "Web Address"
		* Description of "Description"
		* Description of "Target"
		* Description of "Link Relationships (XFN)"
	- Tags
		* Description of "Tag name"
		* Description of "Tag slug"
	- Widgets
		* Text indicating that widgets are added from the listing of widgets on the left.
	- Install Themes
		* Help text for search field
		* Help text for "Feature Filter"
	- Manage Plugins
		* Description of what plugins are and that they are activated/deactivated on that page
		* Text indicating that broken plugins can be renamed to remove them from being active
		* Description of the "Recently Active Plugins" section
	- Add New Plugins
		* Help text for search field
		* Help text for "Popular Tags"
		

Compatible with WordPress 2.8+, 2.9+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/admin-expert-mode.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Each user who wishes to enable expert mode must do so individually by going into their profile, checking the field labeled 'Expert mode', and then pressing the 'Update Profile' button.

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

if ( !class_exists('AdminExpertMode') ) :

class AdminExpertMode {
	var $admin_options_name = 'c2c_admin_expert_mode';
	var $field_name = 'admin_expert_mode';
	var $prompt = '';
	var $help_text = '';
	var $config = array();
	var $activating = false;
	var $is_active = false; // Has admin expert mode been determined to be active?
	var $is_filtered_true = false; // Was admin expert mode activated via add_filter()?

	function AdminExpertMode() {
		if ( !is_admin() ) return;
		global $pagenow;

		$this->prompt = __('Expert mode');
		$this->help_text = __('Enable expert mode if you are familiar with WordPress and don\'t need the inline documentation in the admin.');
		$this->config[$this->field_name] = 0; // Admin Expert Mode is assumed to be initially off

		add_action('admin_head', array(&$this, 'add_css'));
		add_action( 'admin_notices', array( &$this, 'display_activation_notice' ) );
		add_action( 'activate_'.str_replace( trailingslashit( dirname( dirname( __FILE__ ) ) ), '', __FILE__ ), array( &$this, 'plugin_activated' ) );
		if ( 'profile.php' == $pagenow ) {
			add_action('admin_init', array(&$this, 'maybe_save_options'));
			add_action('profile_personal_options', array(&$this, 'show_option'));
		}
	}

	function plugin_activated() {
		set_transient( 'aem_activated', "show", 10 );
	}

	function display_activation_notice() {
		if ( get_transient( 'aem_activated' ) ) {
			$msg = sprintf( __( '<strong>NOTE:</strong> (Note that you must enable expert mode for yourself (in your <a href="%s" title="Profile">profile</a>) and/or any other user accounts for it to take effect.' ), admin_url( 'profile.php' ) );
			echo "<div id='message' class='updated fade'><p>$msg</p></div>";
		}
	}

	function is_admin_expert_mode_active() {
		if ( $this->is_active ) return true;
		if ( apply_filters( 'c2c_admin_expert_mode', false ) || apply_filters( 'c2c_admin_expert_mode_' . get_user_option( 'user_login' ), false ) ) {
			$this->is_active = true;
			$this->is_filtered_true = true;
		} else {
			$options = $this->get_options();
			if ( $options[$this->field_name] )
				$this->is_active = true;
		}
		return $this->is_active;
	}

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
		#addcat .form-field p, #addtag .form-field p, .install-help { display:none; }
		</style>
CSS;
	}

	function show_option($user) {
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

	function get_options() {
		if ( !empty($this->options) ) return $this->options;
		$existing_options = get_user_option($this->admin_options_name);
		$this->options = wp_parse_args($existing_options, $this->config);
		return $this->options;
	}

	function maybe_save_options() {
		$user = wp_get_current_user();
		if ( isset($_POST['submit']) ) {
			$options = $this->get_options();
			$options[$this->field_name] = $_POST[$this->field_name] ? 1 : 0;
			update_user_option($user->ID, $this->admin_options_name, $options);
			$this->options = $options;
		}
	}

} // end AdminExpertMode

endif; // end if !class_exists()

if ( class_exists('AdminExpertMode') )
	new AdminExpertMode();

?>