<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Container\Array\ArrayContainerResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

class ContainerProcessor implements ProcessorInterface
{
    /**
     * Indexed array with processors to use for processing.
     *
     * @var InterruptProcessor[]
     */
    private array $processors = [];

    /**
     * @inheritdoc
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        $container = new ArrayContainerResult();
        foreach ($this->processors as $processor) {
            $result = $processor->process($value, $context);

            $container->addItem($result);

            if (!$result->isValid() && $processor->mustInterrupt()) {
                break;
            }
        }

        return $container;
    }

    /**
     * Add a processor to the container.
     *
     * When interrupt is true, processing will stop if a processor result is invalid. The default value is false.
     *
     * @param ProcessorInterface $processor
     * @param bool|null          $interrupt
     *
     * @return ContainerProcessor
     */
    public function addProcessor(ProcessorInterface $processor, ?bool $interrupt = null): ContainerProcessor
    {
        $this->processors[] = new InterruptProcessor($processor, $interrupt);

        return $this;
    }
}
