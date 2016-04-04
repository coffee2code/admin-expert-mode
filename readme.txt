=== Admin Expert Mode ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: admin, expert, help, remove inline documentation, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 2.8
Tested up to: 4.5
Stable tag: 2.3

Allow users with access to the administration section to hide inline documentation and help text, which generally target beginning users.


== Description ==

WordPress has long provided inline documentation throughout the administrative interface that provide explanations for various features and input fields. This includes an explanations of excerpts, trackbacks, custom fields, page parents, etc. These are great for newcomers to WordPress, but for those sufficient familiarity these bits of text are no longer necessary and merely provide visual noise. This plugin gets rid of those descriptive texts.

The plugin's behavior is made available as a per-user profile option. Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the 'Update Profile' button.

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

* Themes - Custom Header
    * Help text simply stating you can upload files from you computer

* Manage Plugins
    * Description of what plugins are and that they are activated/deactivated on that page
    * Text indicating that broken plugins can be renamed to remove them from being active
    * Description of the "Recently Active Plugins" section

* Add New Plugins
    * Help text for search field
    * Help text for "Popular Tags"

* Tools
    * Description paragraphs for "Press This"

* Tools - Import
    * Description paragraph for "Import"

* Tools - Export
    * Description paragraphs for "Export"

* Settings - General
    * Extra help text after input fields for "Tagline", "Site address (URL)", "E-mail address", "Timezone"

* Settings - Permalinks
    * Help text about "Common Settings"
    * Help text about "Optional"

NOTE: This plugin does NOT remove input field labels or section headers, nor anything containing actual data. In a few cases, descriptive text is left intact when it is of enough importance to warrant retention, or the markup structure does not facilitate easy removal.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/admin-expert-mode/) | [Plugin Directory Page](https://wordpress.org/plugins/admin-expert-mode/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `admin-expert-mode.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the 'Update Profile' button.


== Screenshots ==

1. A screenshot of some of the panels of the 'Edit Post' admin page *after* this plugin is activated.
2. A screenshot of the same panels of the 'Edit Post' admin page as they appear in a standard WP 3.3 installation. See the difference?
3. A screenshot of the plugin's profile checkbox on the user Profile page.
4. A screenshot of the form on the 'Categories' admin page *after* this plugin is activated.
5. A screenshot of the form on the 'Categories' admin page as they appear in a standard WP 3.3 installation. See the difference?

== Filters ==

The plugin is further customizable via two filters. Typically, these customizations would be put into your active theme's functions.php file, or used by another plugin.

= c2c_admin_expert_mode (filter) =

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

= c2c_admin_expert_mode_default (filter) =

The 'c2c_admin_expert_mode_default' filter allows you to specify whether admin expert mode should be active for users by default or not. This filter only applies to users who visit the admin for the first time after the plugin is activated. Once a user visits the admin, their setting gets set to the default state and will no longer be affected by this filter. If you wish to affect the setting for existing users, use the 'c2c_admin_expert_mode' filter instead.

Arguments:

* $is_active (bool): Boolean indicating if admin expert mode is active by default (default is false)

Example (only valid in WP 3.0+):

`<?php // Enable admin expert mode for all users by default
add_filter( 'c2c_admin_expert_mode_default', '__return_true' );
?>`


== Changelog ==

= 2.3 (2016-04-03) =
* New: Add support for trimming new term.php page.
* New: Add LICENSE file.
* Change: Note compatibility through WP 4.5+.

= 2.2 (2016-01-13) =
* Add: Hide descriptive paragraph for Tools - Import.
* Change: Hide Press This help text on latest WP.
* Change: Add support for language packs:
    * Don't load textdomain from file.
    * Remove .pot file and /lang subdirectory.
* Change: Note compatibility through WP 4.4+.
* Change: Explicitly declare methods in unit tests as public.
* Change: Update copyright date (2016).
* Add: Create empty index.php to prevent files from being listed if web server has enabled directory listings.

= 2.1 (2015-02-17) =
* Reformat plugin header
* Update hiding of a few existing help text
* Use __DIR__ instead of `dirname(__FILE__)`
* Note compatibility through WP 4.1+
* Minor code reformatting (bracing, spacing)
* Various inline code documentation improvements (spacing, punctuation)
* Change documentation links to wp.org to be https
* Update screenshots
* Update copyright date (2015)
* Add plugin icon
* Update .pot

= 2.0 (2013-12-22) =
* Update hiding of 'Install Plugins' page help text for WP 3.8
* Update hiding of 'Permalink Settings' page help text for WP 3.8
* Minor documentation tweaks
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Change donate link
* Update screenshot of profile page
* Add banner

= 1.9 =
* Update hiding of 'Tools - Export' page help text for WP 3.5
* Update hiding of 'Tools' page help text for WP 3.5
* Fix to hide 'Settings - Permalinks' page help text
* Remove `register_profile_page_hooks()`
* Add $user_id to `maybe_save_options()` and remove need to check $_POST
* Add check to prevent execution of code if file is directly accessed
* Modified documentation
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Minor code reformatting (spacing)
* Create repo's assets directory
* Move screenshots into repo's assets directory

= 1.8.1 =
* Use string instead of variable to specify translation textdomain
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

= 1.8 =
* Change activation admin notice to recognize if settings is already true for user and say so
* Hook 'admin_enqueue_scripts' action instead of 'admin_head' to output CSS
* Hook 'load-profile.php' to add action for the profile.php page rather than using pagenow
* Remove load_textdomain() and private static $textdomain_subdir and just load textdomain directly in do_init()
* Add version() to return plugin version
* Add register_styles(), enqueue_admin_css(), register_profile_page_hooks()
* Move all CSS into admin.css
* Remove add_css()
* Change check in maybe_save_options() to ensure profile options are being updated
* Update screenshots for WP 3.3
* Add two new screenshots
* Note compatibility through WP 3.3+
* Create 'lang' subdirectory and move .pot file into it
* Regenerate .pot
* Add link to plugin directory page to readme.txt
* Add 'Domain Path' directive to top of main plugin file
* Minor code reformatting (spacing)
* Update copyright date (2012)

= 1.7.2 =
* Fix accidental hiding of permalink rules on permalinks settings page

= 1.7.1 =
* Fix accidental hiding of submit button on permalinks settings page

= 1.7 =
* Remove more help text on Custom Header, Tools, Settings - General, and Settings - Permalinks pages
* Note compatibility through WP 3.2+
* Tiny code formatting change (spacing)
* Fix plugin homepage and author links in description in readme.txt

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


== Upgrade Notice ==

= 2.3 =
Minor update: updated hiding of help text on the new term.php page, noted compatibility through WP 4.5+, added LICENSE file

= 2.2 =
Minor update: hide a few more recently added/changed help texts, adjustments to utilize language packs, minor unit test tweaks, noted compatibility through WP 4.4+, and updated copyright date

= 2.1 =
Minor update: updated hiding of a few existing help text; noted compatibility through WP 4.1+; updated copyright date (2015); added plugin icon

= 2.0 =
Minor update: updated hiding of a few existing help text; noted compatibility with WP 3.8+

= 1.9 =
Minor update: removed newly added and changed help text; noted compatibility with WP 3.5+

= 1.8.1 =
Trivial update: noted compatibility through WP 3.4+; explicitly stated license

= 1.8 =
Recommended update. Highlights: various code improvements; enqueue CSS; noted compatibility through WP 3.3+

= 1.7.2 =
Bugfix release. Fixed accidental hiding of permalink rules on permalinks settings page

= 1.7.1 =
Bugfix release! Fixed accidental hiding of submit button on permalinks settings page

= 1.7 =
Minor update: removed more help text and noted compatibility with WP 3.2+

= 1.6 =
Minor update: implementation changes; noted compatibility with WP 3.1+ and updated copyright date.

= 1.5 =
Recommended update! Highlights: hides newly added help text, displays reminder on plugin activation, added filters, localization support, dropped pre-WP 2.8 compatibility, added verified WP 3.0 compatibility.
