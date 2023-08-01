<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity;

interface IdentityInterface
{
    /**
     * Get identity identifier.
     *
     * @return mixed
     */
    public function getIdentifier(): mixed;

    /**
     * If identity is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * Get attributes.
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array;

    /**
     * Get attribute.
     *
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getAttribute(string $name, mixed $default = null): mixed;
}
