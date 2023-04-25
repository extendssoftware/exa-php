<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

interface ContainerInterface
{
    /**
     * Find value in container.
     *
     * @param string     $path    Dot notation path.
     * @param mixed|null $default Default value to return when path not found.
     *
     * @return mixed
     */
    public function find(string $path, mixed $default = null): mixed;

    /**
     * Get value from container.
     *
     * @param string $path
     *
     * @return mixed
     * @throws ContainerException When path can not be found in container.
     */
    public function get(string $path): mixed;

    /**
     * Check if container has value.
     *
     * @param string $path Dot notation path.
     *
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * Extract all data.
     *
     * @return object|array<mixed>
     */
    public function extract(): object|array;
}
