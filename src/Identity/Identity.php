<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity;

class Identity implements IdentityInterface
{
    /**
     * Identity constructor.
     *
     * @param mixed                $identifier
     * @param bool                 $isAuthenticated
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        private readonly mixed $identifier,
        private readonly bool  $isAuthenticated,
        private readonly array $attributes = []
    ) {
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

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }
}
