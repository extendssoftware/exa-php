<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\NullValidator;

class NullableValidator extends AbstractProcessor
{
    /**
     * NullableValidator constructor.
     *
     * @param ProcessorInterface $processor
     */
    public function __construct(private readonly ProcessorInterface $processor)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new NullValidator())->process($value, $context);
        if ($result->isValid()) {
            return $result;
        }

        return $this->processor->process($value, $context);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function getTemplates(): array
    {
        return [];
    }
}
