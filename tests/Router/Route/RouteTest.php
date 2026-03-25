<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getPath()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getMethod()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getProcessors()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getParameters()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getName()
     */
    public function testGetMethods(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        /**
         * @var ProcessorInterface $processor
         */
        $route = new Route(
            '/',
            [
                'parameter' => $processor,
            ],
            [
                'foo' => 'bar',
            ],
            'index',
            Method::POST
        );

        $this->assertSame('/', $route->getPath());
        $this->assertSame(Method::POST, $route->getMethod());
        $this->assertSame(['parameter' => $processor], $route->getProcessors());
        $this->assertSame(['foo' => 'bar'], $route->getParameters());
        $this->assertSame('index', $route->getName());
    }
}
