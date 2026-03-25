<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;
use function is_object;

class ObjectValidator extends AbstractProcessor
{
    /**
     * When value is not an object.
     *
     * @const string
     */
    public const string NOT_OBJECT = 'notObject';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if (is_object($value)) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_OBJECT, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_OBJECT => 'Value must be an object, got {{type}}.',
        ];
    }
}
