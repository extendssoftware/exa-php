<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class NotValidator extends AbstractLogicalValidator
{
    /**
     * When value is not false.
     *
     * @const string
     */
    public const string NOT_FALSE = 'notFalse';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (!$value) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_FALSE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_FALSE => 'Value is not equal to false.',
        ];
    }
}
