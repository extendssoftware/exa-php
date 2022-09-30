<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Role\RoleInterface;

interface AuthorizationInfoInterface
{
    /**
     * Get authorization permissions.
     *
     * @return PermissionInterface[]
     */
    public function getPermissions(): array;

    /**
     * Get authorization roles.
     *
     * @return RoleInterface[]
     */
    public function getRoles(): array;
}
