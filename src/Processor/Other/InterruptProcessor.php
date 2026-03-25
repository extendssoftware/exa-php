<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

readonly class InterruptProcessor implements ProcessorInterface
{
    /**
     * Set processor and interrupt flag.
     *
     * @param ProcessorInterface $processor
     * @param bool               $interrupt
     */
    public function __construct(private ProcessorInterface $processor, private ?bool $interrupt = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        return $this->processor->process($value, $context);
    }

    /**
     * Return the interrupt flag.
     *
     * @return bool
     */
    public function mustInterrupt(): bool
    {
        return $this->interrupt ?? false;
    }
}
