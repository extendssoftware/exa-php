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
        ?array                  $validators = null,
        private readonly ?bool  $strict = true
    ) {
        foreach ($validators ?? [] as $name => $validator) {
            $this->addProperty($name, $validator);
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
        if (!isset($this->properties[$dependent])) {
            if ($this->strict) {
                return $this->getInvalidResult(self::VALIDATOR_MISSING, [
                    'property' => $dependent,
                ]);
            }

            return $this->getValidResult();
        }

        return $this->properties[$dependent]->getValidator()->validate($value, $context);
    }

    /**
     * Add validator for property value.
     *
     * An existing validator for property value will be overwritten.
     *
     * @param string             $name
     * @param ValidatorInterface $validator
     *
     * @return PropertyDependentValidator
     */
    public function addProperty(string $name, ValidatorInterface $validator): PropertyDependentValidator
    {
        $this->properties[$name] = new Property($name, $validator);

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
