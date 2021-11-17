# Changelog

## 2.9 _(2021-11-16)_

### Highlights:

This minor release adds DEVELOPER-DOCS.md, notes compatibility through WP 5.8+, reorganizes unit tests, and minor tweaks.

### Details:

* New: Add DEVELOPER-DOCS.md and move hooks documentation into it
* Change: Add newlines after output block tags and remove newline after label tag
* Change: Add translator comments to explain placeholders
* Change: Use stricter equality check in a conditional statement
* Change: Tweak inline function documentation (typo, verb tenses, bullet list syntax)
* Change: Tweak installation instruction
* Change: Note compatibility through WP 5.8+
* Change: Change a tag in readme.txt header
* Unit tests:
    * Change: Use stricter regex in a couple tests
    * Change: Restructure unit test file structure
        * Change: Move `tests/test-*` into `tests/phpunit/tests/`
        * Change: Move `tests/bootstrap.php` to `tests/phpunit/`
        * Change: Move `bin/` into `phpunit/`
    * Change: In bootstrap, store path to plugin file constant so its value can be used within that file and in test file
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0
    * Change: Remove 'test-' prefix from unit test files
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* New: Add a few more possible TODO items

## 2.8 _(2021-03-24)_

### Highlights:

This release is a recommended minor update that hides text in the welcome panel on the dashboard page, hides text on the "Settings - Writing" and "Settings - Reading" pages, and notes compatibility through WP 5.7+.

### Details:

* New: Hide the description of the dashboard's welcome panel
* New: Hide the label for the "Update Services" on the "Settings - Writing" page
* New: Hide the extra description for the "Search engine visibility" on the "Settings - Reading" page
* Fix: Fix typo in plugin description
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

## 2.7 _(2020-06-14)_

### Highlights:

This release is a recommended minor update that updates hiding of text on "Privacy Settings" page, hides text on "Media Settings" page, hides text relating to avatars on "Discussion Setting" page, adds a TODO.md file, updates a few URLs to be HTTPS, expands unit testing, and notes compatibility through WP 5.4+.

### Details:

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

## 2.6 _(2019-12-22)_

### Highlights:

This release is a minor update that fixes a bug with considering the user setting value, verifies compatibility through WordPress 5.3+, adds unit testing, and makes minor behind-the-scenes improvements.

### Details:

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

## 2.5 _(2019-04-04)_

### Highlights:

This minor release primarily adds the ability for admin users to see and edit the setting within other users' profiles and notes compatilibity through WordPress v5.1+. All other changes were behind-the-scenes for the general improvement of the plugin and its documentation.

### Details:

* New: Permit admins to see and edit the value of the setting for other users
* Change: Add user ID as an additional argument to the `c2c_admin_expert_mode_default` filter
* Change: Allow `get_options()` to accept a user ID and memoize values by user ID
* Change: Initialize plugin on `plugins_loaded` action instead of on load
* Change: Merge `do_init()` into `init()`
* Change: Modify help text for checkbox
* Change: Cast return value of `c2c_admin_expert_mode` filter as boolean
* Change: (Hardening) Escape output of user profile URL
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add inline documentation for hooks
* Change: Improve function docblocks by adding missing `@see`, `@access`, `@param`, and `@return` tags
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Split paragraph in README.md's "Support" section into two

## 2.4 _(2018-04-13)_
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
* Change: Cast value returned from `c2c_admin_expert_mode_default` filter as bool
* Change: Remove unused private static variable `$activating`
* Change: Tweak readme.txt (minor content changes, spacing)
* Change: Tweak plugin description
* Change: Add GitHub link to readme
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Note compatibility through WP 4.9+
* Change: Update copyright date (2018)
* Change: Update installation instruction to prefer built-in installer over .zip file

## 2.3 _(2016-04-03)_
* New: Add support for trimming new term.php page.
* New: Add LICENSE file.
* Change: Note compatibility through WP 4.5+.

## 2.2 _(2016-01-13)_
* Add: Hide descriptive paragraph for Tools - Import.
* Change: Hide Press This help text on latest WP.
* Change: Add support for language packs:
    * Don't load textdomain from file.
    * Remove .pot file and /lang subdirectory.
* Change: Note compatibility through WP 4.4+.
* Change: Explicitly declare methods in unit tests as public.
* Change: Update copyright date (2016).
* Add: Create empty index.php to prevent files from being listed if web server has enabled directory listings.

## 2.1 _(2015-02-17)_
* Reformat plugin header
* Update hiding of a few existing help text
* Use `__DIR__` instead of `dirname(__FILE__)`
* Note compatibility through WP 4.1+
* Minor code reformatting (bracing, spacing)
* Various inline code documentation improvements (spacing, punctuation)
* Change documentation links to wp.org to be https
* Update screenshots
* Update copyright date (2015)
* Add plugin icon
* Update .pot

## 2.0 _(2013-12-22)_
* Update hiding of 'Install Plugins' page help text for WP 3.8
* Update hiding of 'Permalink Settings' page help text for WP 3.8
* Minor documentation tweaks
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Change donate link
* Update screenshot of profile page
* Add banner

## 1.9
* Update hiding of 'Tools - Export' page help text for WP 3.5
* Update hiding of 'Tools' page help text for WP 3.5
* Fix to hide 'Settings - Permalinks' page help text
* Remove `register_profile_page_hooks()`
* Add `$user_id` to `maybe_save_options()` and remove need to check $_POST
* Add check to prevent execution of code if file is directly accessed
* Modified documentation
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Minor code reformatting (spacing)
* Create repo's assets directory
* Move screenshots into repo's assets directory

## 1.8.1
* Use string instead of variable to specify translation textdomain
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

## 1.8
* Change activation admin notice to recognize if settings is already true for user and say so
* Hook `admin_enqueue_scripts` action instead of `admin_head` to output CSS
* Hook `load-profile.php` to add action for the profile.php page rather than using pagenow
* Remove `load_textdomain()` and private static $textdomain_subdir and just load textdomain directly in do_init()
* Add `version()` to return plugin version
* Add `register_styles()`, `enqueue_admin_css()`, `register_profile_page_hooks()`
* Move all CSS into admin.css
* Remove `add_css()`
* Change check in `maybe_save_options()` to ensure profile options are being updated
* Update screenshots for WP 3.3
* Add two new screenshots
* Note compatibility through WP 3.3+
* Create 'lang' subdirectory and move .pot file into it
* Regenerate .pot
* Add link to plugin directory page to readme.txt
* Add 'Domain Path' directive to top of main plugin file
* Minor code reformatting (spacing)
* Update copyright date (2012)

## 1.7.2
* Fix accidental hiding of permalink rules on permalinks settings page

## 1.7.1
* Fix accidental hiding of submit button on permalinks settings page

## 1.7
* Remove more help text on Custom Header, Tools, Settings - General, and Settings - Permalinks pages
* Note compatibility through WP 3.2+
* Tiny code formatting change (spacing)
* Fix plugin homepage and author links in description in readme.txt

## 1.6
* Rename class from `AdminExpertMode` to `c2c_AdminExpertMode`
* Switch from object instantiation to direct class invocation
* Explicitly declare all functions public static and class variables private static
* Add .pot file
* Documentation tweaks
* Note compatibility through WP 3.1+
* Update copyright date (2011)

## 1.5
* Display notice on plugin's activation to remind admin that expert mode must be enabled for each user before it takes effect for the user
* Allow configuring of default expert mode state for all users, via `c2c_admin_expert_mode_default` filter (initially set to false)
* Allow enabling of expert mode for all users, via `c2c_admin_expert_mode` filter (having it return true)
* Add true localization support
* Handle text on 'Install Themes' page
* Handle text on 'Add New Plugins'
* Check for `is_admin()` before defining class rather than during constructor
* Add function `is_admin_expert_mode_active()` to encapsulate logic to determine if expert mode is active for the current user
* Change plugin description
* Instantiate object within primary `class_exists()` check
* Assign object instance to global variable `$c2c_admin_expert_mode` to allow for external manipulation
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

## 1.1
* Additionally hide inline docs on categories and tags admin pages
* Noted WP 2.8+ compatibility

## 1.0
* Initial release
