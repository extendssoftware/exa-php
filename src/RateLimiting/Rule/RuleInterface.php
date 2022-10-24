<?php

namespace ExtendsSoftware\ExaPHP\RateLimiting\Rule;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;

interface RuleInterface
{
    /**
     * Get rule permission.
     *
     * @return PermissionInterface
     */
    public function getPermission(): PermissionInterface;

    /**
     * Get rule options.
     *
     * @return mixed[]
     */
    public function getOptions(): array;

    /**
     * Get rule option.
     *
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getOption(string $name, mixed $default = null): mixed;
}
