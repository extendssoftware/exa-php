<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Query;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Query\Exception\InvalidQueryString;
use ExtendsSoftware\ExaPHP\Router\Route\Query\Exception\QueryParameterMissing;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatch;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class QueryRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * @param mixed[] $validators
     * @param mixed[] $parameters
     */
    public function __construct(private readonly array $validators, private readonly array $parameters = [])
    {
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
        foreach ($extra['validators'] ?? [] as $parameter => $validator) {
            if (is_string($validator)) {
                $validator = [
                    'name' => $validator,
                ];
            }

            $validators[$parameter] = $serviceLocator->getService($validator['name'], $validator['options'] ?? []);
        }

        return new QueryRoute($validators, $extra['parameters'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $query = $request
            ->getUri()
            ->getQuery();

        $matched = [];
        foreach ($this->validators as $path => $validator) {
            if (array_key_exists($path, $query)) {
                $value = $query[$path];

                $result = $validator->validate($value, $query);
                if (!$result->isValid()) {
                    throw new InvalidQueryString($path, $result);
                }

                $matched[$path] = $value;
            } elseif (!array_key_exists($path, $this->parameters)) {
                throw new QueryParameterMissing($path);
            }
        }

        return new RouteMatch(array_replace($this->parameters, $matched), $pathOffset);
    }

    /**
     * @inheritDoc
     */
    public function assemble(RequestInterface $request, array $path, array $parameters): RequestInterface
    {
        $query = [];
        $uri = $request->getUri();

        $parameters = array_replace($this->parameters, $uri->getQuery(), $parameters);
        foreach ($this->validators as $parameter => $validator) {
            if (!array_key_exists($parameter, $parameters)) {
                throw new QueryParameterMissing($parameter);
            }

            $result = $validator->validate($parameters[$parameter]);
            if (!$result->isValid()) {
                throw new InvalidQueryString($parameter, $result);
            }

            if (($this->parameters[$parameter] ?? null) !== ($parameters[$parameter] ?? null)) {
                $query[$parameter] = $parameters[$parameter];
            }
        }

        return $request->withUri($uri->withQuery(array_replace($uri->getQuery(), $query)));
    }
}
