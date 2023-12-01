<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Cache;

interface CacheInterface
{
    /**
     * Get value.
     *
     * @param string $key     The key for value.
     * @param mixed  $default Default value when key not set.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set value.
     *
     * @param string   $key   The key for value.
     * @param mixed    $value The value to set.
     * @param int|null $ttl   Time to life in seconds.
     *
     * @return static
     */
    public function set(string $key, mixed $value, int $ttl = null): static;

    /**
     * Is cache has key.
     *
     * @param string $key The key to check.
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Delete key and value.
     *
     * @param string $key
     *
     * @return $this
     */
    public function delete(string $key): static;

    /**
     * Clean cache.
     *
     * @return static
     */
    public function clean(): static;
}
