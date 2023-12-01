<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

use ExtendsSoftware\ExaPHP\Utility\Container\Exception\PathNotFound;

class Container implements ContainerInterface
{
    /**
     * Container constructor.
     *
     * @param object|array<mixed> $data
     * @param string              $delimiter
     */
    public function __construct(private object|array $data = [], private readonly string $delimiter = '.')
    {
    }

    /**
     * @inheritDoc
     */
    public function find(string $path, mixed $default = null): mixed
    {
        $reference = &$this->data;
        $segments = $this->getSegments($path);
        foreach ($segments as $segment) {
            if (is_object($reference) && property_exists($reference, $segment)) {
                $reference = &$reference->{$segment};
            } elseif (is_array($reference) && array_key_exists($segment, $reference)) {
                $reference = &$reference[$segment];
            } else {
                return $default;
            }
        }

        return $reference;
    }

    /**
     * @inheritDoc
     */
    public function get(string $path): mixed
    {
        $value = $this->find($path, $this);
        if ($value === $this) {
            throw new PathNotFound($path);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function has(string $path): bool
    {
        return $this->find($path, $this) !== $this;
    }

    /**
     * @inheritDoc
     */
    public function extract(): object|array
    {
        return $this->data;
    }

    /**
     * Explode path to segments.
     *
     * @param string $path
     *
     * @return array<string>
     */
    private function getSegments(string $path): array
    {
        return explode($this->delimiter ?: '.', $path);
    }
}
