<?php

namespace UFCOE;

/**
 * Implements default access levels specified here:
 * http://community.education.ufl.edu/community/pg/pages/view/32409/
 */
class AccessCase_Default implements AccessCaseInterface
{
    public function isFinal()
    {
        return false;
    }

    public function alterAccessLevel($level, AccessLevelContext $ctx)
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
