<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Algorithm;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Rule\RuleInterface;

interface AlgorithmInterface
{
    /**
     * Consume rule for identity.
     *
     * @param RuleInterface     $rule
     * @param IdentityInterface $identity
     *
     * @return QuotaInterface|null
     */
    public function consume(RuleInterface $rule, IdentityInterface $identity): ?QuotaInterface;
}
