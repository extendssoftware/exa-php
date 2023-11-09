<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class InArrayValidator extends AbstractValidator
{
    /**
     * When value not in array.
     *
     * @var string
     */
    public const NOT_IN_ARRAY = 'notInArray';

    /**
     * InArrayValidator constructor.
     *
     * @param array<mixed> $array
     * @param bool|null    $strict
     */
    public function __construct(private readonly array $array, private readonly ?bool $strict = null)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        if (!in_array($value, $this->array, $this->strict ?? false)) {
            return $this->getInvalidResult(self::NOT_IN_ARRAY, [
                'value' => $value,
                'array' => $this->array,
            ]);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_IN_ARRAY => 'Value {{value}} is not allowed in array, only {{values}} are allowed.',
        ];
    }
}
