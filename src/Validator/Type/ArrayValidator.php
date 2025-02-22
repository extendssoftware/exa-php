<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

use function gettype;
use function is_array;

class ArrayValidator extends AbstractValidator
{
    /**
     * When value is not an array.
     *
     * @const string
     */
    public const string NOT_ARRAY = 'notArray';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_array($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_ARRAY, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ARRAY => 'Value must be an array, got {{type}}.',
        ];
    }
}
