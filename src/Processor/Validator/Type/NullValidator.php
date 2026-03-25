<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function gettype;

class NullValidator extends AbstractProcessor
{
    /**
     * When value is not null.
     *
     * @const string
     */
    public const string NOT_NULL = 'notNull';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        if ($value === null) {
            return $this->getValidResult($value);
        }

        return $this->getInvalidResult(self::NOT_NULL, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NULL => 'Value must be null, got {{type}}.',
        ];
    }
}
