<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Callback;

use Closure;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

readonly class CallbackValidator implements ValidatorInterface
{
    /**
     * CallbackValidator constructor.
     *
     * @param Closure $callback
     */
    public function __construct(private Closure $callback)
    {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        return ($this->callback)($value, $context);
    }
}
