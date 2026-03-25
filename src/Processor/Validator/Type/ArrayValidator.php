<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;
use function is_array;

class ArrayValidator extends AbstractProcessor
{
    /**
     * When value is not an array.
     *
     * @const string
     */
    public const string NOT_ARRAY = 'notArray';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_array($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_ARRAY, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ARRAY => 'Value must be an array, got {{type}}.',
        ];
    }
}
