<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;

readonly class Property
{
    /**
     * Property constructor.
     *
     * @param int|string         $name
     * @param ProcessorInterface $processor
     */
    public function __construct(private int|string $name, private ProcessorInterface $processor)
    {
    }

    /**
     * Get property name.
     *
     * @return int|string
     */
    public function getName(): int|string
    {
        return $this->name;
    }

    /**
     * Get property processor.
     *
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }
}
