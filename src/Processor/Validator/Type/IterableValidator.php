<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;
use function is_iterable;

class IterableValidator extends AbstractProcessor
{
    /**
     * When value is not iterable.
     *
     * @const string
     */
    public const string NOT_ITERABLE = 'notIterable';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_iterable($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_ITERABLE, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ITERABLE => 'Value must be iterable, got {{type}}.',
        ];
    }
}
