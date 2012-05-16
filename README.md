**Note!** This directory must be named `ufcoe_access_defaults` in your mod folder.

Provides the following features:

* API for forcing particular access levels on new content
* Includes two modifiers by default (can be removed by other plugins)
* Modifier "Issue4525" removes public/logged_in access levels for items in closed groups, since [they don't work](http://trac.elgg.org/ticket/4525).
* Modifier "Default" implements this strategy: removes friends and friend collections from options within groups; removes public/logged_in options for write access; within groups, the default access becomes the group; write access for user content outside groups defaults to the user (private).

## Design

When this plugin is activated, the `input/access` and `input/write_access` views are overridden and cause the output of `get_default_access()` to be passed through a plugin hook. During that hook a set of modifier objects are run on the access level and can decide to change it if they wish.

## To influence access levels

To make a modifier, write a class that implements `UFCOE\AccessDefaults\ModifierInterface` and store it in your own plugin. Look at `Modifier_Default` as an example. Your `modifyAccessLevel()` method will receive a context object that provides information you might find useful to make a decision about access level.

If you want your instance to be the final modifier, have its `isFinal()` method return `true`.

In your plugin, register for the `"ufcoe_access_defaults:alter", "before"` plugin hook, and, in its handler function, `$returnvalue` will contain an array of modifier instances. Add your instance to the array and return the array.

The plugin does this to add its own modifier, so in your hook handler you can remove it or place your modifier at the beginning of the array, etc.
