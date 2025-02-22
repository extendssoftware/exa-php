<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class BooleanValidator extends AbstractValidator
{
    /**
     * When value is not boolean.
     *
     * @const string
     */
    public const string NOT_BOOLEAN = 'notBoolean';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_bool($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_BOOLEAN, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_BOOLEAN => 'Value must be a boolean, got {{type}}.',
        ];
    }
}
