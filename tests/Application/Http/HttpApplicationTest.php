<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Http;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class HttpApplicationTest extends TestCase
{
    /**
     * Run.
     *
     * Test that middleware chain will be proceed with request.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Http\HttpApplication::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\Http\HttpApplication::run()
     */
    public function testRun(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var MiddlewareChainInterface $chain
         * @var RequestInterface         $request
         * @var ServiceLocatorInterface  $serviceLocator
         */
        $application = new HttpApplication($chain, $request, $serviceLocator, []);
        $application->bootstrap();
    }
}
