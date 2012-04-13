**Note!** This directory must be named `ufcoe_access_defaults` in your mod folder.

Provides the following features:

* API for forcing particular access levels on new content
* Includes a default strategy for influencing access levels (can be removed by other plugins)

## Design

When this plugin is activated, the `input/access` and `input/write_access` views are overridden and cause the output of `get_default_access()` to be passed through a plugin hook. During that hook a set of modifier objects are run on the access level and can decide to change it if they wish.

## To influence access levels

To make a modifier, write a class that implements `UFCOE\AccessDefaults\ModifierInterface` and store it in your own plugin. Look at `Modifier_Default` as an example. Your `modifyAccessLevel()` method will receive a context object that provides information you might find useful to make a decision about access level.

If you want your instance to be the final modifier, have its `isFinal()` method return `true`.

In your plugin, register for the `"ufcoe_access_defaults:alter", "before"` plugin hook, and, in its handler function, `$returnvalue` will contain an array of modifier instances. Add your instance to the array and return the array.

The plugin does this to add its own modifier, so in your hook handler you can remove it or place your modifier at the beginning of the array, etc.
