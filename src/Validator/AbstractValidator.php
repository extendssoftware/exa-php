<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * Create valid result.
     *
     * @return ResultInterface
     */
    protected function getValidResult(): ResultInterface
    {
        return new ValidResult();
    }

    /**
     * Create invalid result.
     *
     * When template can not be found, an exception will be thrown.
     *
     * @param string       $code
     * @param mixed[]|null $parameters
     *
     * @return ResultInterface
     * @throws TemplateNotFound
     */
    protected function getInvalidResult(string $code, array $parameters = null): ResultInterface
    {
        $templates = $this->getTemplates();
        if (!array_key_exists($code, $templates)) {
            throw new TemplateNotFound($code);
        }

        return new InvalidResult($code, $templates[$code], $parameters ?? []);
    }

    /**
     * Get an associative array with templates to use for invalid result.
     *
     * @return mixed[]
     */
    abstract protected function getTemplates(): array;
}
