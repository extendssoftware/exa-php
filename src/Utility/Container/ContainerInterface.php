<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

interface ContainerInterface
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
     * Check if container has value.
     *
     * @param string $path Dot notation path.
     *
     * @return bool
     */
    public function has(string $path): bool;

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
}