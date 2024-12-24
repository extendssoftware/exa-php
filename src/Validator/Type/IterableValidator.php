<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

use function gettype;
use function is_iterable;

class IterableValidator extends AbstractValidator
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
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (is_iterable($value)) {
            return $this->getValidResult();
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
