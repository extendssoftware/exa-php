<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class LessOrEqualValidator extends AbstractValidator
{
    /**
     * When value is not less than or equal to context.
     *
     * @const string
     */
    public const string NOT_LESS_OR_EQUAL = 'notLessOrEqual';

    /**
     * LessOrEqualValidator constructor.
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
        if ($value <= $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_LESS_OR_EQUAL, [
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
            self::NOT_LESS_OR_EQUAL => 'Value {{value}} is not less than or equal to subject {{subject}}.',
        ];
    }
}
