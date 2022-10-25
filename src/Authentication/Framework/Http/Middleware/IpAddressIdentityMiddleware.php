<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\Identity;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;

class IpAddressIdentityMiddleware implements MiddlewareInterface
{
    /**
     * AnonymousIdentityMiddleware constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(private readonly StorageInterface $storage)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $this->storage->setIdentity(
            new Identity(
                $request->getServerParameter('Remote-Addr'),
                false
            )
        );

        return $chain->proceed($request);
    }
}
