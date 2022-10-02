<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Method\Exception;

use ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRouteException;
use LogicException;

class MethodNotAllowed extends LogicException implements MethodRouteException
{
    /**
     * MethodNotAllowed constructor.
     *
     * @param string   $method
     * @param string[] $allowedMethods
     */
    public function __construct(private readonly string $method, private array $allowedMethods)
    {
        parent::__construct(
            sprintf(
                'Method "%s" is not allowed.',
                $method
            )
        );
    }

    /**
     * Get not allowed method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get all allowed methods.
     *
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return array_merge(array_unique($this->allowedMethods));
    }

    /**
     * Add allowed methods.
     *
     * @param string[] $methods
     *
     * @return MethodNotAllowed
     */
    public function addAllowedMethods(array $methods): MethodNotAllowed
    {
        $this->allowedMethods = array_merge($this->allowedMethods, $methods);

        return $this;
    }
}
