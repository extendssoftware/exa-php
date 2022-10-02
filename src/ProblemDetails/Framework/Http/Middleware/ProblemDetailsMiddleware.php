<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetailsInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\SerializerInterface;

class ProblemDetailsMiddleware implements MiddlewareInterface
{
    /**
     * ProblemMiddleware constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $response = $chain->proceed($request);
        $body = $response->getBody();
        if ($body instanceof ProblemDetailsInterface) {
            $serialized = $this->serializer->serialize($body);

            $response = $response
                ->withHeader('Content-Type', 'application/problem+json')
                ->withHeader('Content-Length', (string)strlen($serialized))
                ->withStatusCode($body->getStatus())
                ->withBody($serialized);
        }

        return $response;
    }
}
