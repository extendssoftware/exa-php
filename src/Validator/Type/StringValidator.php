<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class StringValidator extends AbstractValidator
{
    /**
     * When value is not a string.
     *
     * @const string
     */
    public const NOT_STRING = 'notString';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_string($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_STRING, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_STRING => 'Value must be a string, got {{type}}.',
        ];
    }
}
