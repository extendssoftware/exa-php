<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor;

use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;

use function array_key_exists;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * Create valid result.
     *
     * @param mixed $value The validated value.
     *
     * @return ResultInterface
     */
    protected function getValidResult(mixed $value): ResultInterface
    {
        return new ValidResult($value);
    }

    /**
     * Create an invalid result.
     *
     * When a template cannot be found, an exception will be thrown.
     *
     * @param string       $code
     * @param mixed[]|null $parameters
     *
     * @return ResultInterface
     * @throws TemplateNotFound
     */
    protected function getInvalidResult(string $code, ?array $parameters = null): ResultInterface
    {
        $templates = $this->getTemplates();
        if (!array_key_exists($code, $templates)) {
            throw new TemplateNotFound($code);
        }

        return new InvalidResult($code, $templates[$code], $parameters ?? []);
    }

    /**
     * Get an associative array with templates to use for an invalid result.
     *
     * @return mixed[]
     */
    abstract protected function getTemplates(): array;
}
