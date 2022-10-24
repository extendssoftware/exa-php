<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Permission;

interface PermissionInterface
{
    /**
     * Get permission notation.
     *
     * @return string
     */
    public function getNotation(): string;

    /**
     * Check if this permission implies permission.
     *
     * @param PermissionInterface $permission
     *
     * @return bool
     */
    public function implies(PermissionInterface $permission): bool;
}
