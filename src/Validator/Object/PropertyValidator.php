<?php

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\AbstractValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Type\ObjectValidator;

use function property_exists;

class PropertyValidator extends AbstractValidator
{
    /**
     * When object property can not be found.
     *
     * @const string
     */
    public const string OBJECT_PROPERTY_NOT_FOUND = 'objectPropertyNotFound';

    /**
     * PropertyValidator constructor.
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
    public function validate(mixed $value, mixed $context = null): ResultInterface
    {
        $result = (new ObjectValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (!property_exists($value, $this->property)) {
            return $this->getInvalidResult(self::OBJECT_PROPERTY_NOT_FOUND, [
                'property' => $this->property,
            ]);
        }

        return $this->getValidResult();
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
