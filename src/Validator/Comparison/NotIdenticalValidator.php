<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class NotIdenticalValidator extends AbstractValidator
{
    /**
     * When value is not identical to context.
     *
     * @const string
     */
    public const string IS_IDENTICAL = 'isIdentical';

    /**
     * NotIdenticalValidator constructor.
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
        if ($value !== $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::IS_IDENTICAL, [
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
            self::IS_IDENTICAL => 'Value {{value}} is identical to subject {{subject}}.',
        ];
    }
}
