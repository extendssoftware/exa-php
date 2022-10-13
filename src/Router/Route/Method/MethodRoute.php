<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Method;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\InvalidRequestBody;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatch;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class MethodRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_GET = 'GET';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    /**
     * Create a method route.
     *
     * @param string  $method
     * @param mixed[] $parameters
     * @param mixed[] $validators
     */
    public function __construct(
        private readonly string $method,
        private readonly array  $parameters = [],
        private readonly array  $validators = []
    ) {
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): RouteInterface {
        $validators = [];
        foreach ($extra['validators'] ?? [] as $validator) {
            if (is_string($validator)) {
                $validator = [
                    'name' => $validator,
                ];
            }

            $validators[] = $serviceLocator->getService($validator['name'], $validator['options'] ?? []);
        }

        /** @phpstan-ignore-next-line */
        return new MethodRoute($extra['method'], $extra['parameters'] ?? [], $validators);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset, string $name): ?RouteMatchInterface
    {
        $method = $request->getMethod();
        if (strtoupper($method) === $this->method) {
            foreach ($this->validators as $validator) {
                $result = $validator->validate($request->getBody());
                if (!$result->isValid()) {
                    throw new InvalidRequestBody($result);
                }
            }

            return new RouteMatch($this->parameters, $pathOffset, $name);
        }

        throw new MethodNotAllowed($method, [$this->method]);
    }

    /**
     * @inheritDoc
     */
    public function assemble(RequestInterface $request, array $path, array $parameters): RequestInterface
    {
        return $request->withMethod($this->method);
    }
}
