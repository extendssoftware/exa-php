<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\Http;

use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\IpAddressIdentityMiddleware;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use PHPUnit\Framework\TestCase;

class IpAddressIdentityMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that IP address identity will be set in identity storage.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\IpAddressIdentityMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\IpAddressIdentityMiddleware::process()
     */
    public function testProcess(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->once())
            ->method('setIdentity')
            ->with($this->callback(function (IdentityInterface $identity): bool {
                $this->assertSame('127.0.0.1', $identity->getIdentifier());
                $this->assertSame(false, $identity->isAuthenticated());

                return true;
            }))
            ->willReturnSelf();

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getServerParameter')
            ->with('Remote-Addr')
            ->willReturn('127.0.0.1');

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var StorageInterface         $storage
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new IpAddressIdentityMiddleware($storage);

        $this->assertSame($response, $middleware->process($request, $chain));
    }
}
