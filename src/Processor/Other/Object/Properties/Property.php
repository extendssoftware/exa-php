<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;

readonly class Property
{
    /**
     * Property constructor.
     *
     * @param string             $name
     * @param ProcessorInterface $processor
     */
    public function __construct(private string $name, private ProcessorInterface $processor)
    {
    }

    /**
     * Get property name.
     *
     * @return string
     */
    public function getName(): string
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
