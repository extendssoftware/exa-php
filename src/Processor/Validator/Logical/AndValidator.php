<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class AndValidator extends AbstractLogicalValidator
{
    /**
     * @inheritDoc
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        foreach ($this->getProcessors() as $processor) {
            $result = $processor->process($value, $context);
            if (!$result->isValid()) {
                return $result;
            }
        }

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
