<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use LogicException;

class MethodNotAllowed extends LogicException implements RouterException
{
    /**
     * MethodNotAllowed constructor.
     *
     * @param Method   $method
     * @param Method[] $allowedMethods
     */
    public function __construct(private readonly Method $method, private array $allowedMethods)
    {
        parent::__construct(
            sprintf(
                'Method "%s" is not allowed.',
                $method->value
            )
        );
    }

    /**
     * Get not allowed method.
     *
     * @return Method
     */
    public function getMethod(): Method
    {
        return $this->method;
    }

    /**
     * Get all allowed methods.
     *
     * @return array<Method>
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
