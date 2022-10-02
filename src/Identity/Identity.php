<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity;

class Identity implements IdentityInterface
{
    /**
     * Identity constructor.
     *
     * @param mixed $identifier
     */
    public function __construct(private readonly mixed $identifier)
    {
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): mixed
    {
        return $this->identifier;
    }
}
