<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Middleware\Chain;

use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use SplPriorityQueue;

class MiddlewareChain implements MiddlewareChainInterface
{
    /**
     * Set priority queue.
     *
     * @param SplPriorityQueue<int, MiddlewareInterface> $queue
     */
    public function __construct(private SplPriorityQueue $queue = new SplPriorityQueue())
    {
    }

    /**
     * Clone middleware chain.
     *
     * Make sure the same queue is not referenced from the cloned middleware chain. Middlewares inside the queue are not
     * cloned. Be sure they are stateless because they can be called more than once.
     *
     * @return void
     */
    public function __clone()
    {
        $this->queue = clone $this->queue;
    }

    /**
     * @inheritDoc
     */
    public function proceed(RequestInterface $request): ResponseInterface
    {
        $middleware = $this->queue->current();
        if ($middleware instanceof MiddlewareInterface) {
            $this->queue->next();

            return $middleware->process($request, $this);
        }

        return new Response();
    }

    /**
     * Add middleware to the chain.
     *
     * When no priority is given, 1 will be used. Middlewares with the same priority will be processed randomly.
     *
     * @param MiddlewareInterface $middleware
     * @param int|null            $priority
     *
     * @return MiddlewareChain
     */
    public function addMiddleware(MiddlewareInterface $middleware, int $priority = null): MiddlewareChain
    {
        $this->queue->insert($middleware, $priority ?: 1);

        return $this;
    }
}
