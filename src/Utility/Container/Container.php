<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

use ArrayIterator;
use ExtendsSoftware\ExaPHP\Utility\Flattener\Flattener;
use ExtendsSoftware\ExaPHP\Utility\Flattener\FlattenerInterface;
use ExtendsSoftware\ExaPHP\Utility\Merger\Merger;
use ExtendsSoftware\ExaPHP\Utility\Merger\MergerException;
use ExtendsSoftware\ExaPHP\Utility\Merger\MergerInterface;
use Traversable;

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
    public function set(string $path, mixed $value, bool $append = null): static
    {
        $reference = &$this->data;
        $segments = $this->getSegments($path);
        foreach ($segments as $segment) {
            if (!isset($reference[$segment])) {
                $reference[$segment] = [];
            }

            $reference = &$reference[$segment];
        }

        $value = $this->convertObjectsToArrays($value);
        if ($append) {
            if (!is_array($reference) || !array_is_list($reference)) {
                $reference = [$reference];
            }

            $reference[] = $value;
        } else {
            $reference = $value;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unset(string $path): static
    {
        $reference = &$this->data;
        $segments = $this->getSegments($path);
        $lastSegment = array_pop($segments);
        foreach ($segments as $segment) {
            if (!isset($reference[$segment]) || !is_array($reference[$segment])) {
                return $this;
            }

            $reference = &$reference[$segment];
        }

        unset($reference[$lastSegment]);

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
    public function empty(): bool
    {
        return empty($this->data);
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
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has((string)$offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get((string)$offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set((string)$offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->unset((string)$offset);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->extract();
    }

    /**
     * Get value from container.
     *
     * @param string $name Dot notation path.
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    /**
     * Set value to container.
     *
     * @param string $name  Dot notation path.
     * @param mixed  $value Value to set.
     *
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    /**
     * If container has value.
     *
     * @param string $name Dot notation path.
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->has($name);
    }

    /**
     * Unset value from container.
     *
     * @param string $name Dot notation path.
     *
     * @return void
     */
    public function __unset(string $name): void
    {
        $this->unset($name);
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
