<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;

abstract class AbstractLogicalValidator extends AbstractProcessor
{
    /**
     * Processors to process.
     *
     * @var ProcessorInterface[]
     */
    private array $processors = [];

    /**
     * AbstractLogicalValidator constructor.
     *
     * @param ProcessorInterface[]|null $processors
     */
    public function __construct(?array $processors = null)
    {
        foreach ($processors ?? [] as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * Add processor.
     *
     * @param ProcessorInterface $processor
     *
     * @return AbstractLogicalValidator
     */
    public function addProcessor(ProcessorInterface $processor): AbstractLogicalValidator
    {
        $this->processors[] = $processor;

        return $this;
    }

    /**
     * Get processors.
     *
     * @return ProcessorInterface[]
     */
    protected function getProcessors(): array
    {
        return $this->processors;
    }
}
