<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;

interface RouteInterface
{
    /**
     * Get path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Get processors.
     *
     * @return array<string, ProcessorInterface>
     */
    public function getProcessors(): array;

    /**
     * Get parameters.
     *
     * @return array<string, mixed>
     */
    public function getParameters(): array;

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get method.
     *
     * @return Method
     */
    public function getMethod(): Method;
}
