<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use ExtendsSoftware\ExaPHP\Http\Request\Request;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\Uri;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidQueryString;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidRequestBody;
use ExtendsSoftware\ExaPHP\Router\Exception\MethodNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Exception\NotFound;
use ExtendsSoftware\ExaPHP\Router\Exception\PathParameterMissing;
use ExtendsSoftware\ExaPHP\Router\Exception\QueryParametersNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Exception\RouteNotFound;
use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinitionInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatch;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;

use function array_diff_key;
use function array_keys;
use function array_slice;
use function count;
use function current;
use function explode;
use function filter_var;
use function implode;
use function intval;
use function is_array;
use function parse_str;
use function parse_url;
use function str_starts_with;
use function strval;
use function substr;
use function trim;

class Router implements RouterInterface
{
    /**
     * Route definitions.
     *
     * @var array<RouteDefinitionInterface>
     */
    private array $definitions = [];

    /**
     * @inheritDoc
     */
    public function route(RequestInterface $request): RouteMatchInterface
    {
        $allowedMethods = [];
        foreach ($this->definitions as $definition) {
            $route = $definition->getRoute();
            $routeUrl = $this->parseUrl($route->getPath());
            $requestUrl = $this->parseUrl($request->getUri()->toRelative());

            if (count($routeUrl['path']) !== count($requestUrl['path'])) {
                continue;
            }

            $parameters = $route->getParameters();
            $validators = $route->getValidators();
            foreach ($routeUrl['path'] as $index => $part) {
                if (str_starts_with($part, ':')) {
                    $parameter = substr($part, 1);
                    $value = $this->sanitizeValue($requestUrl['path'][$index]);

                    if (isset($validators[$parameter])) {
                        $result = $validators[$parameter]->validate($value);
                        if (!$result->isValid()) {
                            continue 2;
                        }
                    }

                    $parameters[$parameter] = $value;
                } elseif ($requestUrl['path'][$index] !== $part) {
                    continue 2;
                }
            }

            foreach ($routeUrl['query'] as $parameter => $default) {
                if (isset($requestUrl['query'][$parameter])) {
                    $value = $this->sanitizeValue($requestUrl['query'][$parameter]);

                    if (isset($validators[$parameter])) {
                        $result = $validators[$parameter]->validate($value);
                        if (!$result->isValid()) {
                            throw new InvalidQueryString($parameter, $result);
                        }
                    }

                    $data = $value;
                } else {
                    $data = $this->sanitizeValue($default);
                }

                if (is_array($data) && current($data) === null) {
                    // Empty array definition always contains an empty value (which is cast to null), remove it and
                    // don't touch defined values.
                    $data = array_slice($data, 1);
                }

//                if ((is_array($data) && count($data)) || (is_string($data) && strlen($data)) || is_int($data)) {
                $parameters[$parameter] = $data;
//                }
            }

            $notAllowed = array_diff_key($requestUrl['query'], $routeUrl['query']);
            if ($notAllowed) {
                throw new QueryParametersNotAllowed(array_keys($notAllowed));
            }

            if ($request->getMethod() !== $route->getMethod()) {
                $allowedMethods[] = $route->getMethod();

                continue;
            }

            if (isset($validators['body'])) {
                $result = $validators['body']->validate($request->getBody());
                if (!$result->isValid()) {
                    throw new InvalidRequestBody($result);
                }
            }

            return new RouteMatch($definition, $parameters);
        }

        if ($allowedMethods) {
            throw new MethodNotAllowed($request->getMethod(), $allowedMethods);
        }

        throw new NotFound($request);
    }

    /**
     * @inheritDoc
     */
    public function assemble(string $name, array $parameters = null): RequestInterface
    {
        foreach ($this->definitions as $definition) {
            $route = $definition->getRoute();
            if ($route->getName() !== $name) {
                continue;
            }

            $routeUrl = $this->parseUrl($route->getPath());

            $path = [];
            foreach ($routeUrl['path'] as $part) {
                if (str_starts_with($part, ':')) {
                    $parameter = substr($part, 1);
                    if (!isset($parameters[$parameter])) {
                        throw new PathParameterMissing($parameter);
                    }

                    $path[] = $parameters[$parameter];
                } else {
                    $path[] = $part;
                }
            }

            $query = [];
            foreach ($routeUrl['query'] as $parameter => $default) {
                if (isset($parameters[$parameter])) {
                    $query[$parameter] = $parameters[$parameter];
                }
            }

            return (new Request())
                ->withMethod($route->getMethod())
                ->withUri(
                    (new Uri())
                        ->withPath('/' . implode('/', $path))
                        ->withQuery($query)
                );
        }

        throw new RouteNotFound($name);
    }

    /**
     * Add route definition.
     *
     * @param RouteDefinitionInterface ...$definitions
     *
     * @return static
     */
    public function addDefinition(RouteDefinitionInterface ...$definitions): static
    {
        foreach ($definitions as $definition) {
            $this->definitions[] = $definition;
        }

        return $this;
    }

    /**
     * Parse URL.
     *
     * Explode path on forward slash en parse query string.
     *
     * @param string $url
     *
     * @return array{path: array<int, string>, query: array<string, string|array<int, string|null>|null>}
     */
    private function parseUrl(string $url): array
    {
        $parsed = parse_url($url);
        $return = [
            'path' => [],
            'query' => [],
        ];

        if (is_array($parsed)) {
            if (isset($parsed['path'])) {
                $return['path'] = explode('/', trim($parsed['path'], '/'));
            }
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $return['query']);
            }
        }

        return $return;
    }

    /**
     * Sanitize value to desired format.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function sanitizeValue(mixed $value): mixed
    {
        return filter_var($value, FILTER_CALLBACK, [
            'options' => function (mixed $value): mixed {
                return match ($value) {
                    strval(intval($value)) => intval($value),
                    '' => null,
                    default => $value
                };
            }
        ]);
    }
}
