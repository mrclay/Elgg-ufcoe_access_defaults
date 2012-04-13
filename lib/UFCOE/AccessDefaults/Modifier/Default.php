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
            return $ctx->groupAcl;
        } else {
            // user content
            if ($ctx->isWriteAccess) {
                return ACCESS_PRIVATE;
            }
        }
        return $level;
    }
}
