<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;

readonly class Property
{
    /**
     * Property constructor.
     *
     * @param mixed              $value
     * @param ProcessorInterface $processor
     */
    public function __construct(private mixed $value, private ProcessorInterface $processor)
    {
    }

    /**
     * Get property value.
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
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
