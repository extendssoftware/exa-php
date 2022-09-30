<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Link;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Authorization\Role\RoleInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;

interface LinkInterface
{
    /**
     * Get request.
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;

    /**
     * If link is embeddable.
     *
     * @return bool
     */
    public function isEmbeddable(): bool;

    /**
     * Get role.
     *
     * @return RoleInterface|null
     */
    public function getRole(): ?RoleInterface;

    /**
     * Get permission.
     *
     * @return PermissionInterface|null
     */
    public function getPermission(): ?PermissionInterface;

    /**
     * Get policy.
     *
     * @return PolicyInterface|null
     */
    public function getPolicy(): ?PolicyInterface;
}
