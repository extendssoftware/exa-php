<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Rule;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;

readonly class Rule implements RuleInterface
{
    /**
     * Rule constructor.
     *
     * @param PermissionInterface $permission
     * @param mixed[]             $options
     */
    public function __construct(private PermissionInterface $permission, private array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPermission(): PermissionInterface
    {
        return $this->permission;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function getOption(string $name, mixed $default = null): mixed
    {
        return $this->options[$name] ?? $default;
    }
}
