<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Cache\Dummy;

use ExtendsSoftware\ExaPHP\Cache\CacheInterface;

class DummyCache implements CacheInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, int $ttl = null): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clean(): static
    {
        return $this;
    }
}
