<?php
/*
Plugin Name: Admin Expert Mode
Version: 1.1
Plugin URI: http://coffee2code.com/wp-plugins/admin-expert-mode
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Hide all inline documentation in the administration pages for users who are familiar with the various features and input fields of the WordPress admin.

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
	- Manage Plugins
		* Description of what plugins are and that they are activated/deactivated on that page
		* Text indicating that broken plugins can be renamed to remove them from being active
		* Description of the "Recently Active Plugins" section
		

Compatible with WordPress 2.7+, 2.8.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/admin-expert-mode.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Each user who wishes to enable expert mode for themselves must do so individually by going into their profile,
checking the field 'Expert mode', and then pressing the 'Update Profile' button.

*/

/*
Copyright (c) 2009 by Scott Reilly (aka coffee2code)

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

	function AdminExpertMode() {
		if ( !is_admin() ) return;
		global $pagenow;

		$this->prompt = __('Expert mode');
		$this->help_text = __('Enable expert mode if you are familiar with WordPress and don\'t need the inline documentation in the admin.');
		$this->config[$this->field_name] = 0; // Admin Expert Mode is assumed to be initially off

		add_action('admin_head', array(&$this, 'add_css'));
		if ( 'profile.php' == $pagenow ) {
			add_action('admin_init', array(&$this, 'maybe_save_options'));
			add_action('profile_personal_options', array(&$this, 'show_option'));
		}
	}

	function add_css() {
		$options = $this->get_options();
		if ( !$options[$this->field_name] ) return;

		echo <<<CSS
		<style type="text/css">
		#postexcerpt .inside > p, #trackbacksdiv .inside > p:last-child, #postcustom .inside > p, 
		#pagecustomdiv .inside > p, #pagecommentstatusdiv .inside > p:last-child, 
		#pageparentdiv .inside > select + p, #pageparentdiv .inside > p:last-child,
		#namediv .inside > p, #descriptiondiv .inside > p, #linktargetdiv .inside > p,
		#linkxfndiv .inside > p, #addressdiv .inside > p,
		#current-widgets-head #sidebar-info p:last-child,
		#icon-plugins + h2 + p, #currently-active + form + p, #recent-plugins + p, #inactive-plugins + form + h2 + p + p + p,
		#addcat .form-field p, #addtag .form-field p { display:none; }
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