<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Container;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class ContainerResult implements ResultInterface
{
    /**
     * If container is valid.
     *
     * @var bool
     */
    private bool $valid = true;

    /**
     * Validation results.
     *
     * @var ResultInterface[]
     */
    private array $results = [];

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
        $results = array_filter($this->results, static function (ResultInterface $result) {
            return !$result->isValid();
        });

        if (count(array_filter(array_keys($results), 'is_string')) === 0) {
            $results = array_values($results); // Force JSON array by resetting keys.
        }

        return $results;
    }

    /**
     * Get results.
     *
     * @return ResultInterface[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Add result to container.
     *
     * @param ResultInterface $result
     * @param string|null     $name
     *
     * @return ContainerResult
     */
    public function addResult(ResultInterface $result, string $name = null): ContainerResult
    {
        $this->valid = $this->isValid() && $result->isValid();

        if (is_string($name)) {
            $this->results[$name] = $result;
        } else {
            $this->results[] = $result;
        }

        return $this;
    }
}
