<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\ObjectValidator;

use function property_exists;

class PropertyProcessor extends AbstractProcessor
{
    /**
     * When object property can not be found.
     *
     * @const string
     */
    public const string OBJECT_PROPERTY_NOT_FOUND = 'objectPropertyNotFound';

    /**
     * PropertyProcessor constructor.
     *
     * @param string $property
     */
    public function __construct(readonly private string $property)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new ObjectValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (!property_exists($value, $this->property)) {
            return $this->getInvalidResult(self::OBJECT_PROPERTY_NOT_FOUND, [
                'property' => $this->property,
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
            self::OBJECT_PROPERTY_NOT_FOUND => 'Object property {{property}} can not be found.',
        ];
    }
}
