<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Request;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;

interface RequestInterface
{
    /**
     * Merge name and value into existing attributes and return new instance.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return RequestInterface
     */
    public function andAttribute(string $name, mixed $value): RequestInterface;

    /**
     * Add header with name for value.
     *
     * If header with name already exists, it will be added to the array.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return RequestInterface
     */
    public function andHeader(string $name, mixed $value): RequestInterface;

    /**
     * Return custom attributes.
     *
     * @return mixed[]
     */
    public function getAttributes(): array;

    /**
     * Get attribute for key.
     *
     * Default value default will be returned when attribute for key does not exist.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute(string $key, mixed $default = null): mixed;

    /**
     * Return request ID.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Return body.
     *
     * @return mixed
     */
    public function getBody(): mixed;

    /**
     * Return headers.
     *
     * @return mixed[]
     */
    public function getHeaders(): array;

    /**
     * Return server parameters.
     *
     * @return mixed[]
     */
    public function getServerParameters(): array;

    /**
     * Get header value for name.
     *
     * Default value default will be returned when header with name does not exist.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getHeader(string $name, mixed $default = null): mixed;

    /**
     * Get server parameter value for name.
     *
     * Default value default will be returned when server parameter with name does not exist.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getServerParameter(string $name, mixed $default = null): mixed;

    /**
     * Return method.
     *
     * @return Method
     */
    public function getMethod(): Method;

    /**
     * Return request URI.
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface;

    /**
     * Return new instance with attributes.
     *
     * @param mixed[] $attributes
     *
     * @return RequestInterface
     */
    public function withAttributes(array $attributes): RequestInterface;

    /**
     * Return new instance with request ID.
     *
     * @param string $id
     *
     * @return RequestInterface
     */
    public function withId(string $id): RequestInterface;

    /**
     * Return new instance with body.
     *
     * @param mixed $body
     *
     * @return RequestInterface
     */
    public function withBody(mixed $body): RequestInterface;

    /**
     * Set header with name for value.
     *
     * If header with name already exists, it will be overwritten.
     *
     * @param string $name
     * @param string $value
     *
     * @return RequestInterface
     */
    public function withHeader(string $name, string $value): RequestInterface;

    /**
     * Return new instance with headers.
     *
     * @param mixed[] $headers
     *
     * @return RequestInterface
     */
    public function withHeaders(array $headers): RequestInterface;

    /**
     * Return new instance with server parameters.
     *
     * @param mixed[] $parameters
     *
     * @return RequestInterface
     */
    public function withServerParameters(array $parameters): RequestInterface;

    /**
     * Return new instance with method.
     *
     * @param Method $method
     *
     * @return RequestInterface
     */
    public function withMethod(Method $method): RequestInterface;

    /**
     * Return new instance with uri.
     *
     * @param UriInterface|string $uri String will be parsed to an UriInterface instance.
     *
     * @return RequestInterface
     */
    public function withUri(UriInterface|string $uri): RequestInterface;
}
