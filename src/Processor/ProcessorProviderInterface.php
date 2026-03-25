<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor;

interface ProcessorProviderInterface
{
    /**
     * Get processor.
     *
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface;
}
