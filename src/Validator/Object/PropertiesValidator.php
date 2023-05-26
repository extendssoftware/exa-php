<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Object\Properties\Property;
use ExtendsSoftware\ExaPHP\Validator\Other\ProxyValidator;
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
     * Properties.
     *
     * @var array<string, Property>
     */
    private array $properties = [];

    /**
     * ObjectPropertiesValidator constructor.
     *
     * @param array<string, ValidatorInterface>|null $properties
     * @param bool|null                              $strict
     */
    public function __construct(?array $properties = null, private ?bool $strict = null)
    {
        $this->strict ??= true;

        foreach ($properties ?? [] as $property => $validator) {
            $this->addProperty($property, $validator);
        }
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
        foreach ($this->properties as $property) {
            $name = $property->getName();
            if (!property_exists($value, $name)) {
                if (!$property->getValidator() instanceof ProxyValidator) {
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
                    ->validate($value->{$name}, $value),
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
     *
     * @return PropertiesValidator
     */
    public function addProperty(string $property, ValidatorInterface $validator): PropertiesValidator
    {
        $this->properties[$property] = new Property($property, $validator);

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
