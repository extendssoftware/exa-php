<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class AndValidator extends AbstractLogicalValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        foreach ($this->getValidators() as $validator) {
            $result = $validator->validate($value, $context);
            if (!$result->isValid()) {
                return $result;
            }
        }

        return $this->getValidResult();
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
