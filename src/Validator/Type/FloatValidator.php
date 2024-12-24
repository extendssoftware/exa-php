<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

use function gettype;
use function is_float;

class FloatValidator extends AbstractValidator
{
    /**
     * When value is not a float.
     *
     * @const string
     */
    public const string NOT_FLOAT = 'notFloat';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_float($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_FLOAT, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_FLOAT => 'Value must be a float, got {{type}}.',
        ];
    }
}
