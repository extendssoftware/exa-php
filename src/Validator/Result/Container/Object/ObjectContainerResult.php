<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Container\Object;

use ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class ObjectContainerResult extends AbstractContainerResult
{
    /**
     * @inheritDoc
     */
    public function getValue(): object
    {
        $this->assertValid();

        $value = array_map(function ($result) {
            return $result->getValue();
        }, $this->results);

        return (object)$value;
    }

    /**
     * Add result for property.
     *
     * @param string          $property
     * @param ResultInterface $result
     *
     * @return ObjectContainerResult
     */
    public function addProperty(string $property, ResultInterface $result): ObjectContainerResult
    {
        $this->updateValidFlag($result);

        $this->results[$property] = $result;

        return $this;
    }
}
