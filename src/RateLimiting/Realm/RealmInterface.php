<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Realm;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Rule\RuleInterface;

interface RealmInterface
{
    /**
     * Get rate limiting rules for identity.
     *
     * @param IdentityInterface $identity
     *
     * @return RuleInterface[]|null Null when realm has no rules for identity.
     */
    public function getRules(IdentityInterface $identity): ?array;
}
