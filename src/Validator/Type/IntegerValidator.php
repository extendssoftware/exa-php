<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class IntegerValidator extends AbstractValidator
{
    /**
     * When value is not an integer.
     *
     * @const string
     */
    public const NOT_INTEGER = 'notInteger';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_int($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_INTEGER, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_INTEGER => 'Value must be a integer, got {{type}}.',
        ];
    }
}
