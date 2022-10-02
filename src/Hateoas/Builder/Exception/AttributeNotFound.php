<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderException;

class AttributeNotFound extends Exception implements BuilderException
{
    /**
     * AttributeNotFound constructor.
     *
     * @param string $property
     */
    public function __construct(private readonly string $property)
    {
        parent::__construct(
            sprintf(
                'Attribute with property "%s" does not exists.',
                $property
            )
        );
    }

    /**
     * Get attribute property.
     *
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }
}
