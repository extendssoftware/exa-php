<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Algorithm\AlgorithmInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Realm\RealmInterface;

class RateLimiter implements RateLimiterInterface
{
    /**
     * Realms to get rate limiting policies.
     *
     * @var RealmInterface[]
     */
    private array $realms = [];

    /**
     * ...
     *
     * @var AlgorithmInterface[]
     */
    private array $algorithm = [];

    /**
     * @inheritDoc
     */
    public function consume(PermissionInterface $permission, IdentityInterface $identity): ?QuotaInterface
    {
        foreach ($this->realms as $realm) {
            $rules = $realm->getRules($identity);
            if (is_array($rules)) {
                foreach ($this->algorithm as $algorithm) {
                    foreach ($rules as $rule) {
                        if ($rule->getPermission()->implies($permission)) {
                            $quota = $algorithm->consume($rule, $identity);
                            if ($quota) {
                                return $quota;
                            }
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Add algorithm to rate limiter.
     *
     * @param AlgorithmInterface $algorithm
     *
     * @return static
     */
    public function addAlgorithm(AlgorithmInterface $algorithm): static
    {
        $this->algorithm[] = $algorithm;

        return $this;
    }

    /**
     * Add realm to rate limiter.
     *
     * @param RealmInterface $realm
     *
     * @return static
     */
    public function addRealm(RealmInterface $realm): static
    {
        $this->realms[] = $realm;

        return $this;
    }
}
