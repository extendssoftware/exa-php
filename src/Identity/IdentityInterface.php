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
}
