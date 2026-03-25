<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Collection;

use ExtendsSoftware\ExaPHP\Processor\AbstractProcessor;
use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Container\Array\ArrayContainerResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Type\IterableValidator;

class ContainsValidator extends AbstractProcessor
{
    /**
     * CollectionValidator constructor.
     *
     * @param ProcessorInterface $validator
     */
    public function __construct(private readonly ProcessorInterface $validator)
    {
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $result = (new IterableValidator())->process($value);
        if (!$result->isValid()) {
            return $result;
        }

        $container = new ArrayContainerResult();
        foreach ($value as $inner) {
            $container->addItem(
                $this->validator->process($inner, $context),
            );
        }

        return $container;
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
