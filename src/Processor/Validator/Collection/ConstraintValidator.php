<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Collection;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\IterableValidator;

use function count;
use function in_array;

class ConstraintValidator extends AbstractProcessor
{
    /**
     * When there are not allowed values.
     *
     * @var string
     */
    public const string NOT_ALLOWED_VALUES = 'notAllowedValues';

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
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new IterableValidator())->process($value);
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

        return $this->getValidResult($value);
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
