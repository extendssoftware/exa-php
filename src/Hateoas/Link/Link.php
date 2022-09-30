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
     * Request.
     *
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * If link is embeddable.
     *
     * @var bool
     */
    private bool $embeddable;

    /**
     * Role.
     *
     * @var RoleInterface|null
     */
    private ?RoleInterface $role;

    /**
     * Permission.
     *
     * @var PermissionInterface|null
     */
    private ?PermissionInterface $permission;

    /**
     * Policy.
     *
     * @var PolicyInterface|null
     */
    private ?PolicyInterface $policy;

    /**
     * Link constructor.
     *
     * @param RequestInterface         $request
     * @param bool|null                $embeddable
     * @param RoleInterface|null       $role
     * @param PermissionInterface|null $permission
     * @param PolicyInterface|null     $policy
     */
    public function __construct(
        RequestInterface $request,
        bool $embeddable = null,
        RoleInterface $role = null,
        PermissionInterface $permission = null,
        PolicyInterface $policy = null
    ) {
        $this->request = $request;
        $this->embeddable = $embeddable ?? false;
        $this->role = $role;
        $this->permission = $permission;
        $this->policy = $policy;
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
