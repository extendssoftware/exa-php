<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

use ArrayAccess;
use IteratorAggregate;
use JsonSerializable;

interface ContainerInterface extends ArrayAccess, IteratorAggregate, JsonSerializable
{
    /**
     * Get value from container.
     *
     * @param string     $path    Dot notation path.
     * @param mixed|null $default Default value to return when path not found.
     *
     * @return mixed
     */
    public function get(string $path, mixed $default = null): mixed;

    /**
     * Set value to container.
     *
     * @param string $path  Dot notation path.
     * @param mixed  $value Value to set for path.
     *
     * @return $this
     */
    public function set(string $path, mixed $value): static;

    /**
     * Unset value from container.
     *
     * @param string $path Dot notation path.
     *
     * @return $this
     */
    public function unset(string $path): static;

    /**
     * Check if container has value.
     *
     * @param string $path Dot notation path.
     *
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * Check if container is empty.
     *
     * @return bool
     */
    public function empty(): bool;

    /**
     * Clean all container data.
     *
     * @return $this
     */
    public function clear(): static;

    /**
     * Extract all data.
     *
     * @return array<int|string, mixed>
     */
    public function extract(): array;

    /**
     * Merge other container.
     *
     * @param ContainerInterface $container
     *
     * @return $this
     */
    public function merge(ContainerInterface $container): static;

    /**
     * Flatten data.
     *
     * @return array<string, mixed>
     */
    public function flatten(): array;
}
