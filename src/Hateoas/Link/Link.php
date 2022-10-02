<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Link;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Authorization\Role\RoleInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;

class Link implements LinkInterface
{
    /**
     * Link constructor.
     *
     * @param RequestInterface         $request
     * @param bool                     $embeddable
     * @param RoleInterface|null       $role
     * @param PermissionInterface|null $permission
     * @param PolicyInterface|null     $policy
     */
    public function __construct(
        private readonly RequestInterface     $request,
        private readonly bool                 $embeddable = false,
        private readonly ?RoleInterface       $role = null,
        private readonly ?PermissionInterface $permission = null,
        private readonly ?PolicyInterface     $policy = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function isEmbeddable(): bool
    {
        return $this->embeddable;
    }

    /**
     * @inheritDoc
     */
    public function getRole(): ?RoleInterface
    {
        return $this->role;
    }

    /**
     * @inheritDoc
     */
    public function getPermission(): ?PermissionInterface
    {
        return $this->permission;
    }

    /**
     * @inheritDoc
     */
    public function getPolicy(): ?PolicyInterface
    {
        return $this->policy;
    }
}
