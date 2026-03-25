<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties\Property;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

use function array_key_exists;

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
     * @var array<int|string, Property>
     */
    private array $properties = [];

    /**
     * PropertyDependentProcessor constructor.
     *
     * @param string                                     $property
     * @param array<int|string, ProcessorInterface>|null $processors
     * @param bool|null                                  $strict
     */
    public function __construct(
        private readonly string $property,
        ?array $processors = null,
        private readonly ?bool $strict = true
    ) {
        foreach ($processors ?? [] as $name => $processor) {
            $this->addProperty($name, $processor);
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

        $dependent = $context->{$this->property};
        if (!isset($this->properties[$dependent])) {
            if (array_key_exists('*', $this->properties)) {
                $dependent = '*';
            } else {
                if ($this->strict) {
                    return $this->getInvalidResult(self::PROCESSOR_MISSING, [
                        'property' => $dependent,
                    ]);
                }

                return $this->getValidResult($value);
            }
        }

        return $this->properties[$dependent]->getProcessor()->process($value, $context);
    }

    /**
     * Add processor for property value.
     *
     * An existing processor for property value will be overwritten.
     *
     * @param int|string         $name
     * @param ProcessorInterface $processor
     *
     * @return PropertyDependentProcessor
     */
    public function addProperty(int|string $name, ProcessorInterface $processor): PropertyDependentProcessor
    {
        $this->properties[$name] = new Property($name, $processor);

        return $this;
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
