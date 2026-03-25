<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\BooleanValidator;

class FalseValidator extends AbstractProcessor
{
    /**
     * When value is a boolean, but not false.
     *
     * @var string
     */
    public const string NOT_FALSE = 'notFalse';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new BooleanValidator())->process($value, $context);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value !== false) {
            return $this->getInvalidResult(self::NOT_FALSE);
        }

        return $this->getValidResult(false);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_FALSE => 'Value must equals false.',
        ];
    }
}
