<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;
use function is_float;

class FloatValidator extends AbstractProcessor
{
    /**
     * When value is not a float.
     *
     * @const string
     */
    public const string NOT_FLOAT = 'notFloat';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_float($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_FLOAT, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_FLOAT => 'Value must be a float, got {{type}}.',
        ];
    }
}
