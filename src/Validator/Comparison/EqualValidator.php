<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class EqualValidator extends AbstractValidator
{
    /**
     * When value is not equal to context.
     *
     * @const string
     */
    public const string NOT_EQUAL = 'notEqual';

    /**
     * EqualValidator constructor.
     *
     * @param mixed $subject
     */
    public function __construct(private readonly mixed $subject)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($value == $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_EQUAL, [
            'value' => $value,
            'subject' => $this->subject,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_EQUAL => 'Value {{value}} is not equal to subject {{subject}}.',
        ];
    }
}
