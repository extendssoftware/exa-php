<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;
use function is_numeric;

class NumericValidator extends AbstractProcessor
{
    /**
     * When value is not numeric.
     *
     * @const string
     */
    public const string NOT_NUMERIC = 'notNumeric';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_numeric($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_NUMERIC, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NUMERIC => 'Value must be numeric, got {{type}}.',
        ];
    }
}
