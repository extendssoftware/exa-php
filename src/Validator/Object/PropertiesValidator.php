<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Object\Properties\Property;
use ExtendsSoftware\ExaPHP\Validator\Result\Container\ContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\ObjectValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class PropertiesValidator extends AbstractValidator
{
    /**
     * WHen property is not allowed.
     *
     * @var string
     */
    public const PROPERTY_NOT_ALLOWED = 'propertyNotAllowed';

    /**
     * When property is missing.
     *
     * @var string
     */
    public const PROPERTY_MISSING = 'propertyMissing';

    /**
     * ObjectPropertiesValidator constructor.
     *
     * @param mixed[]|null $properties
     * @param bool|null    $strict
     */
    public function __construct(private ?array $properties = null, private ?bool $strict = null)
    {
        $this->properties ??= [];
        $this->strict ??= true;

        foreach ($properties ?? [] as $property => $validator) {
            if (is_array($validator)) {
                [$validator, $optional] = $validator;
            }

            $this->addProperty($property, $validator, $optional ?? null);
        }
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ValidatorInterface {
        $properties = new PropertiesValidator(
            null,
            $extra['strict'] ?? null
        );

        foreach ($extra['properties'] ?? [] as $property) {
            $validator = $serviceLocator->getService(
                $property['validator']['name'],
                $property['validator']['options'] ?? []
            );

            if ($validator instanceof ValidatorInterface) {
                $properties->addProperty($property['property'], $validator, $property['optional'] ?? null);
            }
        }

        return $properties;
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        $result = (new ObjectValidator())->validate($value, $context);
        if (!$result->isValid()) {
            return $result;
        }

        $container = new ContainerResult();
        /** @phpstan-ignore-next-line */
        foreach ($this->properties as $property) {
            $name = $property->getName();
            if (!property_exists($value, $name)) {
                if (!$property->isOptional()) {
                    $container->addResult(
                        $this->getInvalidResult(self::PROPERTY_MISSING, [
                            'property' => $name,
                        ]),
                        $name
                    );
                }

                continue;
            }

            $container->addResult(
                $property
                    ->getValidator()
                    ->validate($value->{$name}, $context ?? $value),
                $name
            );
        }

        if ($this->strict) {
            $this->checkStrictness($container, $value);
        }

        return $container;
    }

    /**
     * Add validator for property.
     *
     * An existing validator for property will be overwritten.
     *
     * @param string             $property
     * @param ValidatorInterface $validator
     * @param bool|null          $optional
     *
     * @return PropertiesValidator
     */
    public function addProperty(
        string             $property,
        ValidatorInterface $validator,
        bool               $optional = null
    ): PropertiesValidator {
        $this->properties[$property] = new Property($property, $validator, $optional);

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::PROPERTY_NOT_ALLOWED => 'Property {{property}} is not allowed on object.',
            self::PROPERTY_MISSING => 'Property {{property}} is missing and can not be left empty.',
        ];
    }

    /**
     * Check strictness.
     *
     * If in strict mode, check if there are more than the allowed properties.
     *
     * @param ContainerResult $container
     * @param mixed           $object
     *
     * @return void
     * @throws TemplateNotFound
     */
    private function checkStrictness(ContainerResult $container, mixed $object): void
    {
        foreach ($object as $property => $value) {
            /** @phpstan-ignore-next-line */
            if (!array_key_exists($property, $this->properties)) {
                $container->addResult(
                    $this->getInvalidResult(self::PROPERTY_NOT_ALLOWED, [
                        'property' => $property,
                    ]),
                    $property
                );
            }
        }
    }
}
