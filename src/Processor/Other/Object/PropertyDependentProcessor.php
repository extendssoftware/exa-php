<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties\Property;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class PropertyDependentProcessor extends AbstractProcessor
{
    /**
     * When property processor is missing.
     *
     * @const string
     */
    public const string PROCESSOR_MISSING = 'processorMissing';

    /**
     * When object property is missing.
     *
     * @const string
     */
    public const string PROPERTY_MISSING = 'propertyMissing';

    /**
     * Properties.
     *
     * @var array<mixed, Property>
     */
    private array $properties = [];

    /**
     * PropertyDependentProcessor constructor.
     *
     * @param string                                $property
     * @param array{mixed, ProcessorInterface}|null $processors
     * @param bool|null                             $strict
     */
    public function __construct(
        private readonly string $property,
        ?array $processors = null,
        private readonly ?bool $strict = true
    ) {
        foreach ($processors ?? [] as $processor) {
            $this->addProperty(...$processor);
        }
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new PropertyProcessor($this->property))->process($context);
        if (!$result->isValid()) {
            if ($this->strict) {
                return $this->getInvalidResult(self::PROPERTY_MISSING, [
                    'property' => $this->property,
                ]);
            }

            return $this->getValidResult($value);
        }

        $propertyValue = $context->{$this->property};
        $property = $this->findProperty($propertyValue);

        if (!$property) {
            $property = $this->findProperty('*');
            if (!$property) {
                if ($this->strict) {
                    return $this->getInvalidResult(self::PROCESSOR_MISSING, [
                        'property' => $propertyValue,
                    ]);
                }

                return $this->getValidResult($value);
            }
        }

        return $property->getProcessor()->process($value, $context);
    }

    /**
     * Add processor for property value.
     *
     * An existing processor for property value will be overwritten.
     *
     * @param mixed              $value
     * @param ProcessorInterface $processor
     *
     * @return PropertyDependentProcessor
     */
    public function addProperty(mixed $value, ProcessorInterface $processor): PropertyDependentProcessor
    {
        $this->properties[$value] = new Property($value, $processor);

        return $this;
    }

    /**
     * Find property for value.
     *
     * @param mixed $value
     *
     * @return Property|null
     */
    protected function findProperty(mixed $value): ?Property
    {
        foreach ($this->properties as $property) {
            if ($property->getValue() === $value) {
                return $property;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::PROCESSOR_MISSING => 'Processor for property {{property}} is missing.',
            self::PROPERTY_MISSING => 'Context object property {{property}} is missing.',
        ];
    }
}
