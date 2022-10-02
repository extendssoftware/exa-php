<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Attribute;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Authorization\Role\RoleInterface;

class Attribute implements AttributeInterface
{
    /**
     * Attribute constructor.
     *
     * @param mixed                    $value
     * @param RoleInterface|null       $role
     * @param PermissionInterface|null $permission
     * @param PolicyInterface|null     $policy
     */
    public function __construct(
        private readonly mixed                $value,
        private readonly ?RoleInterface       $role = null,
        private readonly ?PermissionInterface $permission = null,
        private readonly ?PolicyInterface     $policy = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getValue(): mixed
    {
        return $this->value;
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
