<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class ValidProcessor extends AbstractProcessor
{
    /**
     * @inheritDoc
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        return $this->getValidResult($value);
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
