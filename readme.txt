=== Admin Expert Mode ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: admin, expert, help, remove inline documentation, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 2.8
Tested up to: 5.8
Stable tag: 2.8

Allows users to hide inline documentation and help text that are geared for beginning users in the WordPress admin.


== Description ==

WordPress has long provided inline documentation throughout the administrative interface that provide explanations for various features and input fields. This includes an explanations of excerpts, trackbacks, custom fields, page parents, etc. These are great for newcomers to WordPress, but for those with sufficient familiarity these bits of text are no longer necessary and merely provide visual noise. This plugin gets rid of those descriptive texts.

The plugin's behavior is made available as a per-user profile option. Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the 'Update Profile' button.

Specifically, this plugin removes:

* Dashboard
    * Description of "Welcome to WordPress!" panel

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

* Settings - Writing
    * Label for "Update Services"

* Settings - Reading
    * Extra description for "Search engine visibility"

* Settings - Discussion
    * Description of what avatars are
    * Description of purpose of default avatar

* Settings - Media
    * Text that explains sizes represent maximum dimensions in pixels of images uploaded to Media Library

* Settings - Permalinks
    * Help text about "Common Settings"
    * Help text about "Optional"

* Settings - Privacy
    * Paragraphs of text describing the Privacy Policy page

NOTE: This plugin does NOT remove input field labels or section headers, nor anything containing actual data. In a few cases, descriptive text is left intact when it is of enough importance to warrant retention, or the markup structure does not facilitate easy removal.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/admin-expert-mode/) | [Plugin Directory Page](https://wordpress.org/plugins/admin-expert-mode/) | [GitHub](https://github.com/coffee2code/admin-expert-mode/) | [Author Homepage](https://coffee2code.com)


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

= Does this plugin include unit tests? =

Yes.


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

= 2.8 (2021-03-24) =
Highlights:

* This release is a recommended minor update that hides text in the welcome panel on the dashboard page, hides text on the "Settings - Writing" and "Settings - Reading" pages, and notes compatibility through WP 5.7+.

Details:

* New: Hide the description of the dashboard's welcome panel
* New: Hide the label for the "Update Services" on the "Settings - Writing" page
* New: Hide the extra description for the "Search engine visibility" on the "Settings - Reading" page
* Fix: Fix typo in plugin description
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

= 2.7 (2020-06-14) =
Highlights:

* This release is a recommended minor update that updates hiding of text on "Privacy Settings" page, hides text on "Media Settings" page, hides text relating to avatars on "Discussion Setting" page, adds a TODO.md file, updates a few URLs to be HTTPS, expands unit testing, and notes compatibility through WP 5.4+.

Details:

* New: Hide the descriptions of what avatars and default avatars are on the "Discussion Settings" page
* New: Hide the intro paragraph for the "Media Settings" page
* New: Add TODO.md and move existing TODO list from top of main plugin file into it
* Fix: Re-hide descriptive paragraphs for "Privacy Settings" page
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Unit tests:
    * Change: Enhance `test_default_hooks()` to support testing for direct function callbacks rather than just method callbacks
    * Change: Remove unnecessary unregistering of hooks
    * Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests (and delete commented-out code)

= 2.6 (2019-12-22) =
Highlights:

* This release is a minor update that fixes a bug with considering the user setting value, verifies compatibility through WordPress 5.3+, adds unit testing, and makes minor behind-the-scenes improvements.

Details:

* Fix: Properly account for user setting value
* New: Add unit testing
* New: Add `reset()` to reset plugin to pre-pageload state
* Change: Clarify the inline docs for `c2c_admin_expert_mode_default` filter to indicate it won't override user setting value
* Change: Remove memoization of `$is_active` value
* Change: Allow class to be defined even when loaded outside the admin
* Change: Note compatibility through WP 5.3+
* Change: Add inline documentation for class variables
* Change: Add link to CHANGELOG.md in README.md
* Change: Update copyright date (2020)
* Delete: Remove unused class variable `$config`


== Upgrade Notice ==

= 2.8 =
Recommended minor update: hid text on "Welcome to WordPress!" panel on dashboard, hid text on "Setttings - Writing" and "Settings - Reading" pages, noted compatibility through WP 5.7+, and updated copyright date (2021).

= 2.7 =
Recommended minor update: updated hiding of text on "Privacy Settings" page, hid text on "Media Settings" page, hid text relating to avatars on "Discussion Setting" page, added TODO.md file, updated a few URLs to be HTTPS, expanded unit testing, and noted compatibility through WP 5.4+.

= 2.6 =
Recommended minor update: fixed bug with considering user setting value, added unit testing, noted compatibility through WP 5.3+, created CHANGELOG.md to store historical changelog outside of readme.txt, and updated copyright date (2020).

= 2.5 =
Minor update: permitted admins to see and edit the value of the setting for other users, tweaked plugin initialization, noted compatibility through WP 5.1+, updated copyright date (2019), more.

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
