<?php

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Object\Properties\Property;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class PropertyDependentValidator extends AbstractValidator
{
    /**
     * When property validator is missing.
     *
     * @const string
     */
    public const VALIDATOR_MISSING = 'validatorMissing';

    /**
     * When object property is missing.
     *
     * @const string
     */
    public const PROPERTY_MISSING = 'propertyMissing';

    /**
     * Properties.
     *
     * @var array<string, Property>
     */
    private array $properties = [];

    /**
     * @param string                                 $property
     * @param array<string, ValidatorInterface>|null $validators
     * @param bool|null                              $strict
     */
    public function __construct(
        private readonly string $property,
        private ?array          $validators = null,
        private ?bool           $strict = null
    ) {
        $this->strict ??= true;

        foreach ($validators ?? [] as $value => $validator) {
            $this->addProperty($value, $validator);
        }
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new PropertyValidator($this->property))->validate($context);
        if (!$result->isValid()) {
            if ($this->strict) {
                return $this->getInvalidResult(self::PROPERTY_MISSING, [
                    'property' => $this->property,
                ]);
            }

            return $this->getValidResult();
        }

        $dependent = $context->{$this->property};
        if (!isset($this->validators[$dependent])) {
            if ($this->strict) {
                return $this->getInvalidResult(self::VALIDATOR_MISSING, [
                    'property' => $dependent,
                ]);
            }

            return $this->getValidResult();
        }

        return $this->validators[$dependent]->validate($value, $context);
    }

    /**
     * Add validator for property value.
     *
     * An existing validator for property value will be overwritten.
     *
     * @param string             $value
     * @param ValidatorInterface $validator
     *
     * @return PropertyDependentValidator
     */
    public function addProperty(string $value, ValidatorInterface $validator): PropertyDependentValidator
    {
        $this->validators[$value] = $validator;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::VALIDATOR_MISSING => 'Validator for property {{property}} is missing.',
            self::PROPERTY_MISSING => 'Context object property {{property}} is missing.',
        ];
    }
}
