<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class NullValidator extends AbstractValidator
{
    /**
     * When value is not null.
     *
     * @const string
     */
    public const NOT_NULL = 'notNull';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if ($value === null) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_NULL, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NULL => 'Value must be null, got {{type}}.',
        ];
    }
}
