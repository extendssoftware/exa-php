<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Path;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Path\Exception\PathParameterMissing;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatch;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class PathRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * Create new path route.
     *
     * Value of path must be a part of the request URI, or the whole request URI, path to match. Variables can be used
     * and must start with a semicolon followed by a name. The name must start with a letter and can only consist of
     * alphanumeric characters. When this condition is not matched, the variable will be skipped.
     *
     * The variable name will be checked for the validator given in the validators array. When the variable name is
     * not found as array key, the default validator \w+ will be used.
     *
     * For example: /foo/:bar/:baz/qux
     *
     * @param string  $path
     * @param mixed[] $validators
     * @param mixed[] $parameters
     */
    public function __construct(
        private readonly string $path,
        private readonly array  $validators = [],
        private readonly array  $parameters = []
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
        foreach ($extra['validators'] ?? [] as $parameter => $validator) {
            if (is_string($validator)) {
                $validator = [
                    'name' => $validator,
                ];
            }

            $validators[$parameter] = $serviceLocator->getService($validator['name'], $validator['options'] ?? []);
        }

        /** @phpstan-ignore-next-line */
        return new PathRoute($extra['path'], $validators, $extra['parameters'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset, string $name): ?RouteMatchInterface
    {
        $path = preg_replace_callback(
            '~:([a-z][a-z0-9_]+)~i',
            static function ($match) {
                return sprintf('(?<%s>%s)', $match[1], '[^\/]*');
            },
            $this->path
        );
        $pattern = sprintf('~\G(%s)(/|\z)~', $path);

        if (preg_match(
            $pattern,
            $request
                ->getUri()
                ->getPath(),
            $matches,
            PREG_OFFSET_CAPTURE,
            $pathOffset
        )) {
            foreach ($this->validators as $parameter => $validator) {
                if (!$validator
                    ->validate($matches[$parameter][0])
                    ->isValid()) {
                    return null;
                }
            }

            $parameters = [];
            foreach ($matches as $key => $match) {
                if (is_string($key)) {
                    $parameters[$key] = $match[0];
                }
            }

            /** @phpstan-ignore-next-line */
            return new RouteMatch(array_replace_recursive($this->parameters, $parameters), end($matches)[1], $name);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function assemble(RequestInterface $request, array $path, array $parameters): RequestInterface
    {
        $parameters = array_replace_recursive($this->parameters, $parameters);

        $addition = preg_replace_callback(
            '~:([a-z][a-z0-9_]+)~i',
            static function ($match) use ($parameters) {
                $parameter = $match[1];
                if (!array_key_exists($parameter, $parameters)) {
                    throw new PathParameterMissing($parameter);
                }

                return $parameters[$parameter];
            },
            $this->path
        );

        $uri = $request->getUri();
        return $request->withUri($uri->withPath(rtrim($uri->getPath(), '/') . '/' . ltrim($addition ?: '', '/')));
    }
}
