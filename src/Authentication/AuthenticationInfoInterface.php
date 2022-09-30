<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface AuthenticationInfoInterface
{
    /**
     * Get identity.
     *
     * @return IdentityInterface
     */
    public function getIdentity(): IdentityInterface;
}
