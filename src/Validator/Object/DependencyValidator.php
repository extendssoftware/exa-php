<?php

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class DependencyValidator extends AbstractValidator
{
    /**
     * When property validator can not be found.
     *
     * @const string
     */
    public const VALIDATOR_NOT_FOUND = 'validatorNotFound';

    /**
     * When object property can not be found.
     *
     * @const string
     */
    public const PROPERTY_NOT_FOUND = 'propertyNotFound';

    /**
     * @param string                    $property
     * @param ValidatorInterface[]|null $validators
     */
    public function __construct(private readonly string $property, private ?array $validators = null)
    {
        $this->validators ??= [];
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new DependencyValidator(
            $extra['property'] ?? '',
            $extra['validators'] ?? []
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new PropertyValidator($this->property))->validate($context);
        if (!$result->isValid()) {
            return $this->getInvalidResult(self::PROPERTY_NOT_FOUND, [
                'property' => $this->property,
            ]);
        }

        $dependent = $context->{$this->property};
        if (!isset($this->validators[$dependent])) {
            // exception?
            return $this->getInvalidResult(self::VALIDATOR_NOT_FOUND, [
                'property' => $dependent,
            ]);
        }

        return $this->validators[$dependent]->validate($value, $context);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::VALIDATOR_NOT_FOUND => 'Validator for property {{property}} can not be found.',
            self::PROPERTY_NOT_FOUND => 'Context object property {{property}} can not be found.',
        ];
    }
}
