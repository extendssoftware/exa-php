<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor;

use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

interface ProcessorInterface
{
    /**
     * Process value and context.
     *
     * @param mixed $value
     * @param mixed $context
     *
     * @return ResultInterface
     * @throws ProcessorException
     */
    public function process(mixed $value, mixed $context = null): ResultInterface;
}
