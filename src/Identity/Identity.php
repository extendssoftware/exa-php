<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity;

class Identity implements IdentityInterface
{
    /**
     * Identity constructor.
     *
     * @param mixed $identifier
     * @param bool  $isAuthenticated
     */
    public function __construct(private readonly mixed $identifier, private readonly bool $isAuthenticated)
    {
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): mixed
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }
}
