<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;
use function is_string;

class StringValidator extends AbstractProcessor
{
    /**
     * When value is not a string.
     *
     * @const string
     */
    public const string NOT_STRING = 'notString';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_string($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_STRING, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_STRING => 'Value must be a string, got {{type}}.',
        ];
    }
}
