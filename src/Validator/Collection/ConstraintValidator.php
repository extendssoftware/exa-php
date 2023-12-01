<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator;

class ConstraintValidator extends AbstractValidator
{
    /**
     * When there are not allowed values.
     *
     * @var string
     */
    public const NOT_ALLOWED_VALUES = 'notAllowedValues';

    /**
     * ConstraintValidator constructor.
     *
     * @param array<mixed> $constraints
     */
    public function __construct(private readonly array $constraints)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new IterableValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $notAllowed = [];
        foreach ($value as $inner) {
            if (!in_array($inner, $this->constraints)) {
                $notAllowed[] = $inner;
            }
        }

        if (count($notAllowed)) {
            return $this->getInvalidResult(self::NOT_ALLOWED_VALUES, [
                'not_allowed' => $notAllowed,
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
            self::NOT_ALLOWED_VALUES => 'Values {{not_allowed}} are not allowed in the array.',
        ];
    }
}
