<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Iterator;

class PeekIterator implements PeekIteratorInterface
{
    /**
     * Current iterator position.
     *
     * @var int
     */
    private int $position = 0;

    /**
     * Data to iterate.
     *
     * @var array<int, mixed>
     */
    private array $data;

    /**
     * PeekIterator constructor.
     *
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = array_values($data);
    }

    /**
     * @inheritDoc
     */
    public function current(): mixed
    {
        return $this->data[$this->position];
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * @inheritDoc
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return array_key_exists($this->position, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @inheritDoc
     */
    public function peek(int $positions, mixed $default = null): mixed
    {
        return $this->data[$this->position + abs($positions)] ?? $default;
    }
}
