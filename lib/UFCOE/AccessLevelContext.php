<?php

namespace UFCOE;

/**
 * Encapsulates data you might need to make a decision on access level for a new object
 */
class AccessLevelContext
{
    /**
     * @var bool
     */
    public $inGroup = false;

    /**
     * @var bool
     */
    public $inClosedGroup = false;

    /**
     * @var \ElggGroup
     */
    public $group = null;

    /**
     * @var int
     */
    public $groupAcl = null;

    /**
     * @var \ElggUser
     */
    public $user = null;

    /**
     * @var bool
     */
    public $isAdminUser = false;

    /**
     * @var bool
     */
    public $isWriteAccess = false;

    /**
     * @var int The access level given by get_default_access()
     */
    public $defaultAccessLevel = 0;

    /**
     * @param string $id
     * @return bool
     */
    public function elggInContext($id)
    {
        return elgg_in_context($id);
    }

    /**
     * @return null|string
     */
    public function elggTopContext()
    {
        return elgg_get_context();
    }

    /**
     * @param int $accessLevel determined by get_access_default()
     * @param array $params params passed to the get_default_access plugin hook
     */
    public function __construct($accessLevel, array $params = array())
    {
        $this->defaultAccessLevel = $accessLevel;
        $this->isAdminUser = elgg_is_admin_logged_in();

        if (isset($params['is_write_access'])) {
            $this->isWriteAccess = (bool) $params['is_write_access'];
        } else if (isset($params['input_vars']['name'])
            && (false !== strpos($params['input_vars']['name'], 'write'))) {
            $this->isWriteAccess = true;
        }

        $pageOwner = elgg_get_page_owner_entity();
        if (! $pageOwner) {
            $pageOwner = elgg_get_logged_in_user_entity();
        }
        if ($pageOwner instanceof \ElggGroup) {
            /* @var \ElggGroup $pageOwner */
            $this->inGroup = true;
            $this->group = $pageOwner;
            $this->groupAcl = $pageOwner->get('group_acl');
            $this->inClosedGroup = ! $pageOwner->isPublicMembership();
        } else {
            $this->user = $pageOwner;
        }
    }
}
