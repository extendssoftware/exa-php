<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

readonly class ProxyProcessor implements ProcessorInterface
{
    /**
     * ProxyProcessor constructor.
     *
     * @param ProcessorInterface $processor
     */
    public function __construct(private ProcessorInterface $processor)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $value, mixed $context = null): ResultInterface
    {
        return $this->processor->process($value, $context);
    }
}
