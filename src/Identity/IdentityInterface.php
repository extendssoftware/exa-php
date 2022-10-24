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
}
