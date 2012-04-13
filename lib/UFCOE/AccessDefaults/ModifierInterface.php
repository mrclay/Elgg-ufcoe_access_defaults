<?php

namespace UFCOE\AccessDefaults;

interface ModifierInterface {
    /**
     * If the context suggests a different access level, return it instead of the given level
     *
     * @param int $level current access level under consideration
     * @param LevelContext $ctx
     * @return int
     */
    public function modifyAccessLevel($level, LevelContext $ctx);

    /**
     * Use the access level given by this case; do not try other cases
     * @return bool
     */
    public function isFinal();
}
