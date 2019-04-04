=== Admin Expert Mode ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: admin, expert, help, remove inline documentation, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 2.8
Tested up to: 5.1
Stable tag: 2.4

Alows users to hide inline documentation and help text that are geared for beginning users in the WordPress admin.


== Description ==

WordPress has long provided inline documentation throughout the administrative interface that provide explanations for various features and input fields. This includes an explanations of excerpts, trackbacks, custom fields, page parents, etc. These are great for newcomers to WordPress, but for those with sufficient familiarity these bits of text are no longer necessary and merely provide visual noise. This plugin gets rid of those descriptive texts.

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

* Users - Your Profile
    * Help text for "Biographical Info"
    * Help text for "Sessions"

* Tools
    * Description paragraphs for "Press This"

* Tools - Import
    * Description paragraph for "Import"

* Tools - Export
    * Description paragraphs for "Export"

* Settings - General
    * Extra help text after input fields for "Tagline", "Site Address (URL)", "Email Address", "Timezone"

* Settings - Permalinks
    * Help text about "Common Settings"
    * Help text about "Optional"

* Settings - Privacy
    * Paragraphs of text describing the Privacy Policy page

NOTE: This plugin does NOT remove input field labels or section headers, nor anything containing actual data. In a few cases, descriptive text is left intact when it is of enough importance to warrant retention, or the markup structure does not facilitate easy removal.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/admin-expert-mode/) | [Plugin Directory Page](https://wordpress.org/plugins/admin-expert-mode/) | [GitHub](https://github.com/coffee2code/admin-expert-mode/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `admin-expert-mode.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the checkbox 'Expert mode', and then pressing the 'Update Profile' button.


== Screenshots ==

1. A screenshot of some of the panels of the 'Edit Post' admin page *after* this plugin is activated.
2. A screenshot of the same panels of the 'Edit Post' admin page as they appear in a standard WP 3.3 installation. See the difference?
3. A screenshot of the plugin's profile checkbox on the user Profile page.
4. A screenshot of the form on the 'Categories' admin page *after* this plugin is activated.
5. A screenshot of the form on the 'Categories' admin page as they appear in a standard WP 3.3 installation. See the difference?


== Frequently Asked Questions ==

= Can I view an admin page with admin expert mode turned off without turning the feature off for my account or disabling the plugin entirely? =

Yes. Use the 'disable-admin-expert-mode' query parameter with a value of '1' to disable admin expert mode for the loaded page. This would yield a URL, for example, of `https://example.com/wp-admin/options-general.php?disable-admin-expert-mode=1`. If the URL already has query parameters specified (you'll see a "?" in the URL), then you have to add the new query parameter as an additional one joined with "&", e.g. `https://example.com/wp-admin/post-new.php?post_type=page&disable-admin-expert-mode=1`.


== Hooks ==

The plugin is further customizable via two filters. Code using these filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain). Less ideally, you could put them in your active theme's functions.php file.

**c2c_admin_expert_mode (filter)**

The 'c2c_admin_expert_mode' filter allows you to dynamically determine whether the admin expert mode should be active.

Arguments:

* $is_active (bool): Boolean indicating if admin expert mode is currently active
* $user_login (string): Login of the current user

Example:

`
<?php
// Never let user 'bob' activate admin expert mode
function aem_never_let_bob_activate( $is_active, $user_login ) {
	if ( 'bob' == $user_login )
		return false;
	return $is_active; // Otherwise, preserve activation status for user
}
add_filter( 'c2c_admin_expert_mode', 'aem_never_let_bob_activate', 10, 2 );?>
`

**c2c_admin_expert_mode_default (filter)**

The 'c2c_admin_expert_mode_default' filter allows you to specify whether admin expert mode should be active for users by default or not. This filter only applies to users who visit the admin for the first time after the plugin is activated. Once a user visits the admin, their setting gets set to the default state and will no longer be affected by this filter. If you wish to affect the setting for existing users, use the 'c2c_admin_expert_mode' filter instead.

Arguments:

* $is_active (bool): Boolean indicating if admin expert mode is active by default (default is false)

Example:

`
<?php // Enable admin expert mode for all users by default
add_filter( 'c2c_admin_expert_mode_default', '__return_true' );
?>
`


== Changelog ==

= () =
* Change: Initialize plugin on `plugins_loaded` action instead of on load
* Change: Merge `do_init()` into `init()`
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Split paragraph in README.md's "Support" section into two

= 2.4 (2018-04-13) =
* New: Add ability to disable expert mode for current display of a given page by appending '?disable-admin-expert-mode=1' to the URL
* New: Hide descriptive paragraphs for "Privacy Settings" page
* New: Hide description for "Biographical Info" field in user profile
* New: Hide description for "Sessions" field in user profile
* New: Add README.md
* Change: Delete the user option if the checkbox wasn't checked
* Change: Use `sprintf()` to format output markup rather than concatenating strings and variables
* Change: (Hardening) Check that current user is able to edit the user being edited before saving the user option
* Change: (Hardening) Escape the setting name before use in attributes
* Change: Set the value of the checkbox to '1' instead of 'true'
* Change: Use `checked()` helper function rather than reinventing it
* Change: Cast value returned from 'c2c_admin_expert_mode_default' filter as bool
* Change: Remove unused private static variable `$activating`
* Change: Tweak readme.txt (minor content changes, spacing)
* Change: Tweak plugin description
* Change: Add GitHub link to readme
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Note compatibility through WP 4.9+
* Change: Update copyright date (2018)
* Change: Update installation instruction to prefer built-in installer over .zip file

= 2.3 (2016-04-03) =
* New: Add support for trimming new term.php page.
* New: Add LICENSE file.
* Change: Note compatibility through WP 4.5+.

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/admin-expert-mode/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.4 =
Minor update: added support for query arg to disable expert mode on a given page; updated hiding of newly added help text, added README.md, noted compatibility through WP 4.9+, updated copyright date (2018), and more.

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
