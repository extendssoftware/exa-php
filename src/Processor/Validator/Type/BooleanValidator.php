<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class BooleanValidator extends AbstractProcessor
{
    /**
     * When value is not boolean.
     *
     * @const string
     */
    public const string NOT_BOOLEAN = 'notBoolean';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_bool($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_BOOLEAN, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_BOOLEAN => 'Value must be a boolean, got {{type}}.',
        ];
    }
}
