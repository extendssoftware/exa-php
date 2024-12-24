<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

use function gettype;
use function is_float;
use function is_int;

class NumberValidator extends AbstractValidator
{
    /**
     * When value is not a number.
     *
     * @const string
     */
    public const string NOT_NUMBER = 'notNumber';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_int($value) || is_float($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_NUMBER, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NUMBER => 'Value must be a number, got {{type}}.',
        ];
    }
}
