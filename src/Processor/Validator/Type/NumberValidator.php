<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;
use function is_float;
use function is_int;

class NumberValidator extends AbstractProcessor
{
    /**
     * When value is not a number.
     *
     * @const string
     */
    public const string NOT_NUMBER = 'notNumber';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_int($value) || is_float($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_NUMBER, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NUMBER => 'Value must be a number, got {{type}}.',
        ];
    }
}
