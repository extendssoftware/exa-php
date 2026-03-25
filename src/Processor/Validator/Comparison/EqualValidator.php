<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class EqualValidator extends AbstractProcessor
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
    public function process($value, mixed $context = null): ResultInterface
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($value == $this->subject) {
            return $this->getValidResult($value);
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
