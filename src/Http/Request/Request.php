<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Request;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\Uri;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use Ramsey\Uuid\UuidFactory;
use TypeError;

use function str_ends_with;
use function strlen;

class Request implements RequestInterface, StaticFactoryInterface
{
    /**
     * Request ID.
     *
     * @var string
     */
    private string $id;

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
    public function getId(): string
    {
        return $this->id;
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
    public function withId(string $id): RequestInterface
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withBody(mixed $body): RequestInterface
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

        $body = stream_get_contents($stream);
        if (is_string($body) && strlen($body) > 0) {
            if (isset($headers['Content-Type']) && strtolower($headers['Content-Type']) === 'application/json') {
                $body = json_decode($body, false);
            }
        } else {
            $body = null;
        }

        fclose($stream);

        return (new Request())
            ->withId((new UuidFactory())->uuid4()->toString())
            ->withMethod(Method::from($environment['REQUEST_METHOD']))
            ->withBody($body)
            ->withHeaders($headers)
            ->withServerParameters($parameters)
            ->withUri(Uri::fromEnvironment($environment));
    }
}
