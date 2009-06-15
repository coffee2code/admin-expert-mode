=== Admin Expert Mode ===
Contributors: Scott Reilly
Donate link: http://coffee2code.com/donate
Tags: admin, expert, remove inline documentation, coffee2code
Requires at least: 2.7
Tested up to: 2.8
Stable tag: 1.1
Version: 1.1

Hide all inline documentation in the admin pages for users who are familiar with the various features and input fields of the WordPress admin.

== Description ==

Hide all inline documentation in the administration pages for users who are familiar with the various features and input fields of the WordPress admin.

WordPress 2.7 introduced a variety inline documentation that provide explanations for various feature and input fields (i.e. things like explanations of excerpts, trackbacks, custom fields, page parents, etc).  These are great for newcomers to WordPress.  For those sufficiently familiar with WordPress, these bits of text are no longer necessary and merely provide visual noise.  This plugin gets rid of those descriptive texts.

This plugin's behavior is made available as a per-user profile option.  Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the 'Update Profile' button.

Specifically, this plugin removes:

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

NOTE: This plugin does NOT remove input field labels or section headers, nor anything containing actual data.


== Installation ==

1. Unzip `admin-expert-mode.zip` inside the `/wp-content/plugins/` directory for your site
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Each user who wishes to enable expert mode for themselves must do so individually by going into their profile, checking the field 'Expert mode', and then pressing the 'Update Profile' button.

== Screenshots ==

1. A screenshot of some of the panels of the 'Edit Page' admin page *after* this plugin is activated.
2. A screenshot of the same panels of the 'Edit Page' admin page as they appear in a standard WP 2.7 installation. See the difference?
3. A screenshot of the plugin's profile checkbox on the user Profile page.


