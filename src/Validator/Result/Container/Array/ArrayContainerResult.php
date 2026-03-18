<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Container\Array;

use ExtendsSoftware\ExaPHP\Validator\Result\Container\AbstractContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class ArrayContainerResult extends AbstractContainerResult
{
    /**
     * @inheritDoc
     * @return array<int, mixed>
     */
    public function getValue(): array
    {
        $this->assertValid();

        return array_map(function ($result) {
            return $result->getValue();
        }, $this->results);
    }

    /**
     * Append result.
     *
     * @param ResultInterface $result
     *
     * @return ArrayContainerResult
     */
    public function addItem(ResultInterface $result): ArrayContainerResult
    {
        $this->updateValidFlag($result);

        $this->results[] = $result;

        return $this;
    }
}
