<?php

namespace UFCOE;

interface AccessCaseInterface {
    /**
     * If the context suggests a different access level, return it instead of the given level
     *
     * @param int $level current access level under consideration
     * @param AccessLevelContext $ctx
     * @return int
     */
    public function alterAccessLevel($level, AccessLevelContext $ctx);

    /**
     * Use the access level given by this case; do not try other cases
     * @return bool
     */
    public function isFinal();
}