<?php

namespace UFCOE_Access_Defaults;

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');

function init() {
    elgg_register_plugin_hook_handler('ufcoe:default_access', 'after', __NAMESPACE__ . '\\input_access_handler');

    // add default case
    elgg_register_plugin_hook_handler('ufcoe:alter_access', 'before', __NAMESPACE__ . '\\alter_access_handler');
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
    require_once __DIR__ . '/lib/UFCOE/AccessLevelContext.php';
    require_once __DIR__ . '/lib/UFCOE/AccessCaseInterface.php';

    // context data to be considered for deciding the access level
    $ctx = new \UFCOE\AccessLevelContext($returnvalue, $params);

    // allow plugins to add their own AccessCases to be considered
    $params['context'] = $ctx;
    $cases = elgg_trigger_plugin_hook('ufcoe:alter_access', 'before', $params, array());

    // check the list of cases, each may change the access level and choose to make their
    // decision final
    foreach ($cases as $case) {
        /* @var \UFCOE\AccessCaseInterface $case */
        $returnvalue = $case->alterAccessLevel($returnvalue, $ctx);
        if ($case->isFinal()) {
            break;
        }
    }

    return $returnvalue;
}

/**
 * Add default case for this plugin
 *
 * @param string $hook
 * @param string $type
 * @param array $returnvalue
 * @param array $params
 * @return array
 */
function alter_access_handler($hook, $type, $returnvalue, $params) {
    require_once __DIR__ . '/lib/UFCOE/AccessCase/Default.php';

    $returnvalue[] = new \UFCOE\AccessCase_Default();
    return $returnvalue;
}
