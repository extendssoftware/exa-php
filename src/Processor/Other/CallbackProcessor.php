<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use Closure;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

readonly class CallbackProcessor implements ProcessorInterface
{
    /**
     * CallbackProcessor constructor.
     *
     * @param Closure $callback
     */
    public function __construct(private Closure $callback)
    {
    }

    /**
     * @inheritDoc
     */
    public function process($value, mixed $context = null): ResultInterface
    {
        return ($this->callback)($value, $context);
    }
}
