<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor;

use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class ProcessorPipeline extends AbstractProcessor
{
    /**
     * ProcessorPipeline constructor.
     *
     * @param ProcessorInterface[] $processors
     */
    public function __construct(private readonly array $processors)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $value, mixed $context = null): ResultInterface
    {
        $result = $this->getValidResult($value);

        foreach ($this->processors as $processor) {
            if (!$result->isValid()) {
                return $result;
            }

            $result = $processor->process($result->getValue(), $context);
        }

        return $result;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function getTemplates(): array
    {
        return [];
    }
}
