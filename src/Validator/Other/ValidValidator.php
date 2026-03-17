<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class ValidValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, mixed $context = null): ResultInterface
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
