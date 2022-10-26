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
     * RateLimiter constructor.
     *
     * @param AlgorithmInterface|null $algorithm
     */
    public function __construct(private readonly ?AlgorithmInterface $algorithm = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function consume(PermissionInterface $permission, IdentityInterface $identity): ?QuotaInterface
    {
        foreach ($this->realms as $realm) {
            $rules = $realm->getRules($identity);
            if (is_array($rules) && $this->algorithm) {
                foreach ($rules as $rule) {
                    if ($rule->getPermission()->implies($permission)) {
                        $quota = $this->algorithm->consume($rule, $identity);
                        if ($quota) {
                            return $quota;
                        }
                    }
                }
            }
        }

        return null;
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
