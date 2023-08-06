<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\Container\ContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\ObjectValidator;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use ReflectionObject;

class SchemaValidator extends AbstractValidator
{
    /**
     * When object property is invalid.
     *
     * @var string
     */
    public const INVALID_OBJECT_PROPERTY = 'invalidObjectProperty';

    /**
     * When property value is invalid.
     *
     * @var string
     */
    public const INVALID_PROPERTY_VALUE = 'invalidPropertyValue';

    /**
     * When there are more properties than allowed.
     *
     * @var string
     */
    public const TOO_MANY_PROPERTIES = 'tooManyProperties';

    /**
     * SchemaValidator constructor.
     *
     * @param ValidatorInterface|null $property
     * @param ValidatorInterface|null $value
     * @param int|null                $count
     */
    public function __construct(
        private readonly ?ValidatorInterface $property = null,
        private readonly ?ValidatorInterface $value = null,
        private readonly ?int                $count = null
    ) {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new ObjectValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        $container = new ContainerResult();
        $reflection = new ReflectionObject($value);

        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $name = $property->getName();
            if ($this->property) {
                $result = $this->property->validate($name);
                if (!$result->isValid()) {
                    $container->addResult(
                        $this->getInvalidResult(self::INVALID_OBJECT_PROPERTY, [
                            'property' => $name,
                        ]),
                    );
                }
            }

            $propertyValue = $property->getValue($value);
            if ($this->value) {
                $result = $this->value->validate($propertyValue);
                if (!$result->isValid()) {
                    $container->addResult(
                        $this->getInvalidResult(self::INVALID_PROPERTY_VALUE, [
                            'value' => $propertyValue,
                        ])
                    );
                }
            }
        }

        $propertyCount = count($properties);
        if (is_int($this->count) && $propertyCount > $this->count) {
            $container->addResult(
                $this->getInvalidResult(self::TOO_MANY_PROPERTIES, [
                    'properties' => $propertyCount,
                    'allowed' => $this->count,
                ])
            );
        }

        return $container;
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::INVALID_OBJECT_PROPERTY => 'Object property {{property}} is invalid.',
            self::INVALID_PROPERTY_VALUE => 'Property value {{value}} is invalid.',
            self::TOO_MANY_PROPERTIES => 'There are {{properties}} properties, when only {{allowed} are allowed.',
        ];
    }
}
