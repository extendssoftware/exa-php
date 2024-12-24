<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class GreaterThanValidator extends AbstractValidator
{
    /**
     * When value is not greater than context.
     *
     * @const string
     */
    public const string NOT_GREATER_THAN = 'notGreaterThan';

    /**
     * GreaterThanValidator constructor.
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
        if ($value > $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_GREATER_THAN, [
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
            self::NOT_GREATER_THAN => 'Value {{value}} is not greater than subject {{subject}}.',
        ];
    }
}
