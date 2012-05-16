<?php

namespace UFCOE\AccessDefaults;

/**
 * Removes PUBLIC/LOGGED_IN access levels for content inside closed groups
 * since these levels don't work as of 1.8.5
 * http://trac.elgg.org/ticket/4525
 */
class Modifier_Issue4525 implements ModifierInterface
{
	public function isFinal()
	{
		return false;
	}

	public function modifyAccessLevel($level, LevelContext $ctx)
	{

		return $level;
	}

	public function modifyOptions(array $options, LevelContext $ctx)
	{
		if ($ctx->inClosedGroup) {
			unset($options[ACCESS_PUBLIC]);
			unset($options[ACCESS_LOGGED_IN]);
		}
		return $options;
	}
}
