<?php

namespace UFCOE\AccessDefaults;

/**
 * Implements default access levels specified here:
 * http://community.education.ufl.edu/community/pg/pages/view/32409/
 */
class Modifier_Default implements ModifierInterface
{
    public function isFinal()
    {
        return false;
    }

    public function modifyAccessLevel($level, LevelContext $ctx)
    {

        if ($ctx->inGroup) {
            // default to the group
            return $ctx->groupAcl;
        } else {
            // user content
            if ($ctx->isWriteAccess) {
                // only I can edit stuff I create
                return ACCESS_PRIVATE;
            }
        }
        return $level;
    }

    public function modifyOptions(array $options, LevelContext $ctx)
    {
        if ($ctx->isWriteAccess) {
            // don't allow global write access
            unset($options[ACCESS_PUBLIC]);
            unset($options[ACCESS_LOGGED_IN]);
        }
        if ($ctx->inGroup) {
            // no friends or friend collections
            unset($options[ACCESS_FRIENDS]);
            foreach (array_keys($options) as $acl) {
                if ($acl > 3 && $acl !== $ctx->groupAcl) {
                    unset($options[$acl]);
                }
            }
        } else {
            // user content
        }
        return $options;
    }
}
