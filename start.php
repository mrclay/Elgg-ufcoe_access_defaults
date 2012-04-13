<?php

namespace UFCOE\AccessDefaults;

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');

function init() {
    spl_autoload_register(function ($class) {
        if (0 === strpos($class, __NAMESPACE__ . '\\')) {
            $file = __DIR__ . '/lib/' . strtr($class, '_\\', '//') . '.php';
            is_file($file) && (require $file);
        }
    });

    elgg_register_plugin_hook_handler(shortname() . ':set_default', 'after',
        __NAMESPACE__ . '\\input_access_handler');

    // add default case
    elgg_register_plugin_hook_handler(shortname() . ':alter', 'before',
        __NAMESPACE__ . '\\alter_access_handler');

    elgg_register_plugin_hook_handler('access:collections:write', 'all',
        __NAMESPACE__ . '\\alter_options_handler');
}

/**
 * Get the shortname of this plugin
 * @return string
 */
function shortname() {
    return basename(__DIR__);
}

/**
 * This determines the default access level for new content in our input/access view
 *
 * @param string $hook
 * @param string $type
 * @param int $returnvalue
 * @param array $params
 * @return int
 */
function input_access_handler($hook, $type, $returnvalue, $params) {
    // context data to be considered for deciding the access level
    $ctx = new LevelContext($returnvalue, $params);

    // allow plugins to add their own AccessCases to be considered
    $params['context'] = $ctx;
    $modifiers = get_modifiers($params);

    // check the list of cases, each may change the access level and choose to make their
    // decision final
    foreach ($modifiers as $modifier) {
        /* @var ModifierInterface $modifier */
        $returnvalue = $modifier->modifyAccessLevel($returnvalue, $ctx);
        if ($modifier->isFinal()) {
            break;
        }
    }

    // store this for use in the access:collections:write hook
    set_pop_ctx($ctx);

    return $returnvalue;
}

/**
 * Build/get the list of modifier objects use to alter levels/options
 * @param array $params ignored after the first invocation
 * @return array
 */
function get_modifiers(array $params) {
    static $modifiers = null;
    if ($modifiers === null) {
        $modifiers = elgg_trigger_plugin_hook(shortname() . ':alter', 'before', $params, array());
    }
    return $modifiers;
}

/**
 * Add default modifier for this plugin
 *
 * @param string $hook
 * @param string $type
 * @param array $returnvalue
 * @param array $params
 * @return array
 */
function alter_access_handler($hook, $type, $returnvalue, $params) {
    $returnvalue[] = new Modifier_Default();
    return $returnvalue;
}

/**
 * @param string $hook
 * @param string $type
 * @param array $returnvalue
 * @param array $params
 * @return array
 */
function alter_options_handler($hook, $type, $returnvalue, $params) {
    $ctx = set_pop_ctx();
    if ($ctx) {
        $modifiers = get_modifiers($params);
        foreach ($modifiers as $modifier) {
            /* @var ModifierInterface $modifier */
            $returnvalue = $modifier->modifyOptions($returnvalue, $ctx);
            if ($modifier->isFinal()) {
                break;
            }
        }
    }
    return $returnvalue;
}

/**
 * Set or pop a level context
 * @param null|LevelContext $ctx set to set, null to pop
 * @return null|LevelContext
 */
function set_pop_ctx(LevelContext $ctx = null) {
    static $cache = null;
    if ($ctx) {
        $cache = $ctx;
    } else {
        $ret = $cache;
        $cache = null;
        return $ret;
    }
}
