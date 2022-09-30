<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Permission;

interface PermissionInterface
{
    /**
     * Check if this permission implies permission.
     *
     * @param PermissionInterface $permission
     *
     * @return bool
     */
    public function implies(PermissionInterface $permission): bool;
}
