<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

use ExtendsSoftware\ExaPHP\Utility\Flattener\Flattener;
use ExtendsSoftware\ExaPHP\Utility\Flattener\FlattenerInterface;
use ExtendsSoftware\ExaPHP\Utility\Merger\Merger;
use ExtendsSoftware\ExaPHP\Utility\Merger\MergerException;
use ExtendsSoftware\ExaPHP\Utility\Merger\MergerInterface;

class Container implements ContainerInterface
{
    /**
     * Container constructor.
     *
     * @param mixed              $data
     * @param string             $delimiter
     * @param MergerInterface    $merger
     * @param FlattenerInterface $flattener
     */
    public function __construct(
        private mixed                       $data = [],
        private readonly string             $delimiter = '.',
        private readonly MergerInterface    $merger = new Merger(),
        private readonly FlattenerInterface $flattener = new Flattener()
    ) {
        $this->data = (array)$this->convertObjectsToArrays($data);
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, mixed $default = null): mixed
    {
        $reference = &$this->data;
        $segments = $this->getSegments($path);
        foreach ($segments as $segment) {
            if (!isset($reference[$segment])) {
                return $default;
            }

            $reference = &$reference[$segment];
        }

        return $reference;
    }

    /**
     * @inheritDoc
     */
    public function set(string $path, mixed $value): static
    {
        $reference = &$this->data;
        $segments = $this->getSegments($path);
        foreach ($segments as $segment) {
            if (!isset($reference[$segment]) || !is_array($reference[$segment])) {
                $reference[$segment] = [];
            }

            $reference = &$reference[$segment];
        }

        $reference = $this->convertObjectsToArrays($value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has(string $path): bool
    {
        return $this->get($path, $this) !== $this;
    }

    /**
     * @inheritDoc
     */
    public function clear(): static
    {
        $this->data = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function extract(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     * @throws MergerException
     */
    public function merge(ContainerInterface $container): static
    {
        $this->data = $this->merger->merge($this->data, $container->extract());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function flatten(): array
    {
        return $this->flattener->flatten($this->data, $this->delimiter);
    }

    /**
     * Explode path to segments.
     *
     * @param string $path
     *
     * @return array<mixed>
     */
    private function getSegments(string $path): array
    {
        return explode($this->delimiter ?: '.', $path);
    }

    /**
     * Convert object to array.
     *
     * Object with public properties will be converted to associative array.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function convertObjectsToArrays(mixed $data): mixed
    {
        return json_decode(json_encode($data) ?: '', true);
    }
}
