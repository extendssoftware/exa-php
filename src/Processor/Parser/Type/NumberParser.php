<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Parser\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class NumberParser extends AbstractProcessor
{
    /**
     * When value is not numeric.
     *
     * @const string
     */
    public const string NOT_NUMERIC = 'notNumeric';

    /**
     * @inheritDoc
     */
    public function process(mixed $value, mixed $context = null): ResultInterface
    {
        if (!is_numeric($value)) {
            return $this->getInvalidResult(self::NOT_NUMERIC, [
                'type' => gettype($value),
            ]);
        }

        return $this->getValidResult((float)$value);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NUMERIC => 'Can only parse numeric values, got type {{type}}.',
        ];
    }
}
