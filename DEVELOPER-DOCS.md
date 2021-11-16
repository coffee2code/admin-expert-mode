# Developer Documentation

This plugin provides [hooks](#hooks) for developer usage.

## Hooks

The plugin exposes a number of filters for hooking. Code using these filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

### `c2c_admin_expert_mode` _(filter)_

The `c2c_admin_expert_mode` filter allows you to dynamically determine whether the admin expert mode should be active.

#### Arguments

* `$is_active` _(boolean)_ :
Is admin expert mode currently active? Default is the setting value configured for the current user.

* `$user_login` _(string)_ :
Login of the current user.

#### Example

```php
// Never let user 'bob' activate admin expert mode
add_filter( 'c2c_admin_expert_mode', function(  $is_active, $user_login ) {
	return ( 'bob' === $user_login ) ? false : $is_active;
}, 10, 2 );
```

### `c2c_admin_expert_mode_default` _(filter)_

The `c2c_admin_expert_mode_default` filter allows you to specify whether admin expert mode should be active for users by default or not. This filter only applies to users who visit the admin for the first time after the plugin is activated. Once a user visits the admin, their setting gets set to the default state and will no longer be affected by this filter. If you wish to affect the setting for existing users, use the `c2c_admin_expert_mode` filter instead.all post types are supported.

#### Arguments

* `$is_active` _(boolean)_ :
Is admin expert mode active by default? Default is false.

#### Example

```php
// Enable admin expert mode for all users by default
add_filter( 'c2c_admin_expert_mode_default', '__return_true' );
```
