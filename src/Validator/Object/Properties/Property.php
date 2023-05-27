<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object\Properties;

use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class Property
{
    /**
     * Property constructor.
     *
     * @param string             $name
     * @param ValidatorInterface $validator
     */
    public function __construct(private readonly string $name, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Get property name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get property validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }
}
