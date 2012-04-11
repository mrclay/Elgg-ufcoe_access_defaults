<?php
/**
 * Elgg access level input (core override)
 * Displays a dropdown input field
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options_values'] Array of value => label pairs (overrides default)
 * @uses $vars['name']           The name of the input field
 * @uses $vars['entity']         Optional. The entity for this access control (uses access_id)
 * @uses $vars['class']          Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-access {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-access";
}

$default_level = get_default_access();
$params = array(
    'input_vars' => $vars,
);
$default_level = elgg_trigger_plugin_hook('ufcoe:default_access', 'after', $params, $default_level);

$defaults = array(
	'disabled' => false,
	'value' => $default_level,
	'options_values' => get_write_access_array(),
);

if (isset($vars['entity'])) {
	$defaults['value'] = $vars['entity']->access_id;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

if ($vars['value'] == ACCESS_DEFAULT) {
	$vars['value'] = $default_level;
}

if (is_array($vars['options_values']) && sizeof($vars['options_values']) > 0) {
	echo elgg_view('input/dropdown', $vars);
}
