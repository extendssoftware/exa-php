<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Attribute;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;

interface AttributeInterface
{
    /**
     * Get value.
     *
     * @return mixed
     */
    public function getValue(): mixed;

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
