<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Role;

class Role implements RoleInterface
{
    /**
     * Role constructor.
     *
     * @param string $name
     */
    public function __construct(private readonly string $name)
    {
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function isEqual(RoleInterface $role): bool
    {
        return $this->getName() === $role->getName();
    }
}
