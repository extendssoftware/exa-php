<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Cache\InMemory;

use ExtendsSoftware\ExaPHP\Cache\CacheInterface;

class InMemoryCache implements CacheInterface
{
    /**
     * InMemoryCache constructor.
     *
     * @param mixed[] $cache The cache to start with.
     */
    public function __construct(private array $cache = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, int $ttl = null): static
    {
        $this->cache[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): static
    {
        unset($this->cache[$key]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clean(): static
    {
        $this->cache = [];

        return $this;
    }
}
