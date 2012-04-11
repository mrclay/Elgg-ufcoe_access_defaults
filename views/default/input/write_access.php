<?php
/**
 * Write access (pages override)
 *
 * Removes the public option found in input/access
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['options_values']
 * @uses $vars['name'] The name of the input field
 * @uses $vars['entity'] Optional. The entity for this access control (uses write_access_id)
 */

$options = get_write_access_array();
unset($options[ACCESS_PUBLIC]);

$default_level = get_default_access();
$params = array(
    'input_vars' => $vars,
    'is_write_access' => true,
);
$default_level = elgg_trigger_plugin_hook('ufcoe:default_access', 'after', $params, $default_level);

$defaults = array(
	'class' => 'elgg-input-access',
	'disabled' => FALSE,
	'value' => $default_level,
	'options_values' => $options,
);

if (isset($vars['entity'])) {
	$defaults['value'] = $vars['entity']->write_access_id;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

if ($vars['value'] == ACCESS_DEFAULT) {
	$vars['value'] = $default_level;
}
$vars['value'] = ($vars['value'] == ACCESS_PUBLIC) ? ACCESS_LOGGED_IN : $vars['value'];

echo elgg_view('input/dropdown', $vars);
