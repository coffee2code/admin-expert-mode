# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Define custom caps for being able to edit setting for another user
* Facilitate making the setting apply network-wide (i.e. use update_user_meta(), etc instead)
* If interface is currently being trimmed, add a Help tab panel that reports the interface is being trimmed. e.g. "Does it look like you're lacking inline documentation for input fields? That's likely because you have the 'Expert mode' setting enabled in <a href="">your profile</a>. Uncheck that checkbox to restore display of inline documentation."
* Add filter to selectively prevent display of setting to users (e.g. `c2c_admin_expert_allow_user_config`). (Could be in conjunction with `c2c_admin_expert_mode` filter -- which controls if the feature in general even works for the user -- so it is enabled for everyone and not configurable by individuals, or to hide it for most users except those explicitly listed)
* Remove `@access` docblock keyword usage as it's redundant

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/admin-expert-mode/) or on [GitHub](https://github.com/coffee2code/admin-expert-mode/) as an issue or PR).