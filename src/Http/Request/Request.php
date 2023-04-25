<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Request;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\Uri;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use TypeError;

class Request implements RequestInterface, StaticFactoryInterface
{
    /**
     * Custom request attributes.
     *
     * @var mixed[]
     */
    private array $attributes = [];

    /**
     * Post data.
     *
     * @var mixed
     */
    private mixed $body;

    /**
     * Request headers.
     *
     * @var mixed[]
     */
    private array $headers = [];

    /**
     * Request server parameters.
     *
     * @var mixed[]
     */
    private array $parameters = [];

    /**
     * Request method.
     *
     * @var Method
     */
    private Method $method;

    /**
     * Request URI.
     *
     * @var UriInterface|null
     */
    private ?UriInterface $uri = null;

    /**
     * @inheritDoc
     */
    public function andAttribute(string $name, $value): RequestInterface
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function andHeader(string $name, $value): RequestInterface
    {
        $clone = clone $this;
        if (array_key_exists($name, $clone->headers)) {
            if (!is_array($clone->headers[$name])) {
                $clone->headers[$name] = [
                    $clone->headers[$name],
                ];
            }

            $clone->headers[$name][] = $value;
        } else {
            $clone->headers[$name] = $value;
        }

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): mixed
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name, mixed $default = null): mixed
    {
        return $this->headers[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getServerParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function getServerParameter(string $name, mixed $default = null): mixed
    {
        return $this->parameters[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): Method
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        if ($this->uri === null) {
            $this->uri = new Uri();
        }

        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withAttributes(array $attributes): RequestInterface
    {
        $clone = clone $this;
        $clone->attributes = $attributes;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withBody($body): RequestInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, string $value): RequestInterface
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(array $headers): RequestInterface
    {
        $clone = clone $this;
        $clone->headers = $headers;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withServerParameters(array $parameters): RequestInterface
    {
        $clone = clone $this;
        $clone->parameters = $parameters;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(Method $method): RequestInterface
    {
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return static::fromEnvironment(
            $extra['environment'] ?? $_SERVER,
            $extra['stream'] ?? fopen('php://input', 'r')
        );
    }

    /**
     * Construct from environment variables.
     *
     * @param mixed[] $environment
     * @param mixed   $stream
     *
     * @return RequestInterface
     * @throws TypeError When stream not of type resource.
     */
    public static function fromEnvironment(array $environment, mixed $stream): RequestInterface
    {
        if (!is_resource($stream)) {
            throw new TypeError(sprintf(
                'Stream must be of type resource, %s given.',
                gettype($stream)
            ));
        }

        $headers = [];
        $parameters = [];
        foreach ($environment as $name => $value) {
            $name = str_replace('_', ' ', $name);
            $name = strtolower($name);
            $name = ucwords($name);
            $name = str_replace(' ', '-', $name);

            if (str_starts_with($name, 'Http-')) {
                $headers[substr($name, 5)] = $value;
            } else {
                $parameters[$name] = $value;
            }
        }

        $input = stream_get_contents($stream);
        if (!empty($input)) {
            $body = json_decode($input, false);
        }

        fclose($stream);

        return (new Request())
            ->withMethod(Method::from($environment['REQUEST_METHOD']))
            ->withBody($body ?? null)
            ->withHeaders($headers)
            ->withServerParameters($parameters)
            ->withUri(Uri::fromEnvironment($environment));
    }
}
