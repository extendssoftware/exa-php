<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

class Container implements ContainerInterface
{
    /**
     * Container constructor.
     *
     * @param mixed       $data
     * @param string|null $delimiter
     */
    public function __construct(private mixed $data = [], private readonly ?string $delimiter = null)
    {
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
