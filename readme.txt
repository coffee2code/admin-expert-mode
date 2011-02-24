=== Admin Expert Mode ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: admin, expert, help, remove inline documentation, coffee2code
Requires at least: 2.8
Tested up to: 3.1
Stable tag: 1.6
Version: 1.6

Allow users with access to the administration section to hide inline documentation and help text, which generally target beginning users.


== Description ==

Allow users with access to the administration section to hide inline documentation and help text, which generally target beginning users.

WordPress 2.7 introduced a variety inline documentation that provide explanations for various feature and input fields (i.e. things like explanations of excerpts, trackbacks, custom fields, page parents, etc).  These are great for newcomers to WordPress.  For those sufficiently familiar with WordPress, these bits of text are no longer necessary and merely provide visual noise.  This plugin gets rid of those descriptive texts.

This plugin's behavior is made available as a per-user profile option.  Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the 'Update Profile' button.

Specifically, this plugin removes:

* Categories

    * Description of "Category Name"
    * Description of "Category Slug"
    * Description of "Category Parent"
    * Description of "Description"

* Edit Post
    * Description of "Custom Fields"
    * Description of "Excerpts"
    * Description of "Trackbacks"

* Edit Page
    * Description of comment status
    * Description of "Custom Fields"
    * Verbose descriptions of "Attributes" (parent, template, and order)

* Edit Link
    * Description of "Name"
    * Description of "Web Address"
    * Description of "Description"
    * Description of "Target"
    * Description of "Link Relationships (XFN)"

* Tags
    * Description of "Tag name"
    * Description of "Tag slug"

* Widgets
    * Text indicating that widgets are added from the listing of widgets on the left.

* Install Themes
    * Help text for search field
    * Help text for "Feature Filter"

* Manage Plugins
    * Description of what plugins are and that they are activated/deactivated on that page
    * Text indicating that broken plugins can be renamed to remove them from being active
    * Description of the "Recently Active Plugins" section

* Add New Plugins
    * Help text for search field
    * Help text for "Popular Tags"

NOTE: This plugin does NOT remove input field labels or section headers, nor anything containing actual data.

Links: [Plugin Homepage]:(http://coffee2code.com/wp-plugins/admin-expert-mode/) | [Author Homepage]:(http://coffee2code.com)


== Installation ==

1. Unzip `admin-expert-mode.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the 'Update Profile' button.


== Filters ==

The plugin is further customizable via two filters. Typically, these customizations would be put into your active theme's functions.php file, or used by another plugin.

= c2c_admin_expert_mode =

The 'c2c_admin_expert_mode' filter allows you to dynamically determine whether the admin expert mode should be active.

Arguments:

* $is_active (bool): Boolean indicating if admin expert mode is currently active
* $user_login (string): Login of the current user

Example:

`<?php add_filter( 'c2c_admin_expert_mode', 'aem_never_let_bob_activate', 10, 2 );
// Never let user 'bob' activate admin expert mode
function aem_never_let_bob_activate( $is_active, $user_login ) {
	if ( 'bob' == $user_login )
		return false;
	return $is_active; // Otherwise, preserve activation status for user
} ?>`

= c2c_admin_expert_mode_default =

The 'c2c_admin_expert_mode_default' filter allows you to specify whether admin expert mode should be active for users by default or not.  This filter only applies to users who visit the admin for the first time after the plugin is activated.  Once a user visits the admin, their setting gets set to the default state and will no longer be affected by this filter.  If you wish to affect the setting for existing users, use the 'c2c_admin_expert_mode' filter instead.

Arguments:

* $is_active (bool): Boolean indicating if admin expert mode is active by default (default is false)

Example (only valid in WP 3.0+):

`<?php // Enable admin expert mode for all users by default
add_filter( 'c2c_admin_expert_mode_default', '__return_true' );
?>`


== Changelog ==

= 1.6 =
* Rename class from 'AdminExpertMode' to 'c2c_AdminExpertMode'
* Switch from object instantiation to direct class invocation
* Explicitly declare all functions public static and class variables private static
* Add .pot file
* Documentation tweaks
* Note compatibility through WP 3.1+
* Update copyright date (2011)

= 1.5 =
* Display notice on plugin's activation to remind admin that expert mode must be enabled for each user before it takes effect for the user
* Allow configuring of default expert mode state for all users, via 'c2c_admin_expert_mode_default' filter (initially set to false)
* Allow enabling of expert mode for all users, via 'c2c_admin_expert_mode' filter (having it return true)
* Add true localization support
* Handle text on 'Install Themes' page
* Handle text on 'Add New Plugins'
* Check for is_admin() before defining class rather than during constructor
* Add function is_admin_expert_mode_active() to encapsulate logic to determine if expert mode is active for the current user
* Change plugin description
* Instantiate object within primary class_exists() check
* Assign object instance to global variable $c2c_admin_expert_mode to allow for external manipulation
* Add PHPDoc documentation
* Note compatibility with WP 2.9+ and 3.0+
* Drop compatibility with versions of WP older than 2.8
* Minor code reformatting (spacing)
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Remove trailing whitespace in header docs
* Add Changelog, Upgrade Notice, and Filters sections to readme.txt
* Fix sublist syntax usage in readme.txt
* Add package info to top of plugin file
* Update copyright date

= 1.1 =
* Additionally hide inline docs on categories and tags admin pages
* Noted WP 2.8+ compatibility

= 1.0 =
* Initial release


== Screenshots ==

1. A screenshot of some of the panels of the 'Edit Page' admin page *after* this plugin is activated.
2. A screenshot of the same panels of the 'Edit Page' admin page as they appear in a standard WP 2.7 installation. See the difference?
3. A screenshot of the plugin's profile checkbox on the user Profile page.


== Upgrade Notice ==

= 1.6 =
Minor update: implementation changes; noted compatibility with WP 3.1+ and updated copyright date.

= 1.5 =
Recommended update! Highlights: hides newly added help text, displays reminder on plugin activation, added filters, localization support, dropped pre-WP 2.8 compatibility, added verified WP 3.0 compatibility.