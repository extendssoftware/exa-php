<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Container;

use ExtendsSoftware\ExaPHP\Validator\Result\Exception\ResultNotValid;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

abstract class AbstractContainerResult implements ResultInterface
{
    /**
     * If the container is valid.
     *
     * @var bool
     */
    protected bool $valid = true;

    /**
     * Validation results.
     *
     * @var ResultInterface[]
     */
    protected array $results = [];

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @inheritDoc
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return $this->results;
    }

    /**
     * Check if container is valid.
     *
     * @return void
     * @throws ResultNotValid
     */
    protected function assertValid(): void
    {
        if (!$this->isValid()) {
            throw new ResultNotValid();
        }
    }

    /**
     * Update container status based on current valid flag and result valid flag.
     *
     * @param ResultInterface $result
     *
     * @return void
     */
    protected function updateValidFlag(ResultInterface $result): void
    {
        $this->valid = $this->isValid() && $result->isValid();
    }
}
