<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

interface RouteInterface
{
    /**
     * Get path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Get validators.
     *
     * @return array<string, ValidatorInterface>
     */
    public function getValidators(): array;

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
