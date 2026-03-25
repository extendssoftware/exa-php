<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties\Property;
use ExtendsSoftware\ExaPHP\Processor\Other\ProxyProcessor;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Container\Object\ObjectContainerResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\ObjectValidator;

use function array_key_exists;
use function property_exists;

class PropertiesProcessor extends AbstractProcessor
{
    /**
     * When property is not allowed.
     *
     * @var string
     */
    public const string PROPERTY_NOT_ALLOWED = 'propertyNotAllowed';

    /**
     * When property is missing.
     *
     * @var string
     */
    public const string PROPERTY_MISSING = 'propertyMissing';

    /**
     * Properties.
     *
     * @var array<string, Property>
     */
    private array $properties = [];

    /**
     * PropertiesProcessor constructor.
     *
     * @param array<string, ProcessorInterface>|null $properties
     * @param bool|null                              $strict
     */
    public function __construct(?array $properties = null, private ?bool $strict = null)
    {
        $this->strict ??= true;

        foreach ($properties ?? [] as $property => $processor) {
            $this->addProperty($property, $processor);
        }
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new ObjectValidator())->process($value, $context);
        if (!$result->isValid()) {
            return $result;
        }

        $container = new ObjectContainerResult();
        foreach ($this->properties as $property) {
            $name = $property->getName();
            if (!property_exists($value, $name)) {
                if (!$property->getProcessor() instanceof ProxyProcessor) {
                    $result = $this->getInvalidResult(self::PROPERTY_MISSING, [
                        'property' => $name,
                    ]);

                    $container->addProperty($name, $result);
                }

                continue;
            }

            $result = $property
                ->getProcessor()
                ->process($value->{$name}, $value);
            $container->addProperty($name, $result);
        }

        if ($this->strict) {
            $this->checkStrictness($container, $value);
        }

        return $container;
    }

    /**
     * Add processor for property.
     *
     * An existing processor for property will be overwritten.
     *
     * @param string             $property
     * @param ProcessorInterface $processor
     *
     * @return PropertiesProcessor
     */
    public function addProperty(string $property, ProcessorInterface $processor): PropertiesProcessor
    {
        $this->properties[$property] = new Property($property, $processor);

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
     * @param ObjectContainerResult $container
     * @param mixed                 $object
     *
     * @return void
     * @throws TemplateNotFound
     */
    private function checkStrictness(ObjectContainerResult $container, mixed $object): void
    {
        foreach ($object as $property => $value) {
            if (!array_key_exists($property, $this->properties)) {
                $container->addProperty(
                    $property,
                    $this->getInvalidResult(self::PROPERTY_NOT_ALLOWED, [
                        'property' => $property,
                    ]),
                );
            }
        }
    }
}
