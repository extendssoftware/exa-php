<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;

use function array_key_exists;

abstract class AbstractValidator implements ValidatorInterface
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
