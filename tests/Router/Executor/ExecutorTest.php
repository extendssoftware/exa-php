<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Executor;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Executor\Exception\ParameterValueNotFound;
use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinitionInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ExecutorTest extends TestCase
{
    /**
     * Execute.
     *
     * Test that class method will be executed and response will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Executor\Executor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Executor\Executor::execute()
     */
    public function testExecute(): void
    {
        $service = new class {
            /** @noinspection PhpUnusedParameterInspection */
            public function get(
                RequestInterface    $request,
                RouteMatchInterface $routeMatch,
                string              $first,
                ?string             $second,
                string              $third,
                string              $forth = 'default',
            ): ResponseInterface {
                return new Response();
            }
        };

        $reflectionClass = new ReflectionClass($service);
        $reflectionMethod = $reflectionClass->getMethod('get');

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getReflectionClass')
            ->willReturn($reflectionClass);

        $definition
            ->expects($this->once())
            ->method('getReflectionMethod')
            ->willReturn($reflectionMethod);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->exactly(3))
            ->method('getAttribute')
            ->willReturnCallback(fn($name) => match ([$name]) {
                ['second'],
                ['forth'] => null,
                ['third'] => 'bar',
            });

        $routeMatch = $this->createMock(RouteMatchInterface::class);
        $routeMatch
            ->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        $routeMatch
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'first' => 'foo',
            ]);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->willReturn($service);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         * @var RequestInterface $request
         * @var RouteMatchInterface $routeMatch
         */
        $executor = new Executor($serviceLocator);
        $this->assertInstanceOf(ResponseInterface::class, $executor->execute($request, $routeMatch));
    }

    /**
     * Parameter not found.
     *
     * Test that exception will be thrown when method parameter can not be found.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Executor\Executor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Executor\Executor::execute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Executor\Exception\ParameterValueNotFound::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Executor\Exception\ParameterValueNotFound::getName()
     */
    public function testParameterNotFound(): void
    {
        $service = new class {
            /** @noinspection PhpUnusedParameterInspection */
            public function get(string $first): ResponseInterface
            {
                return new Response();
            }
        };

        $reflectionClass = new ReflectionClass($service);
        $reflectionMethod = $reflectionClass->getMethod('get');

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getReflectionClass')
            ->willReturn($reflectionClass);

        $definition
            ->expects($this->once())
            ->method('getReflectionMethod')
            ->willReturn($reflectionMethod);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('first')
            ->willReturn(null);

        $routeMatch = $this->createMock(RouteMatchInterface::class);
        $routeMatch
            ->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        $routeMatch
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         * @var RequestInterface $request
         * @var RouteMatchInterface $routeMatch
         */
        $executor = new Executor($serviceLocator);

        try {
            $executor->execute($request, $routeMatch);
        } catch (ParameterValueNotFound $exception) {
            $this->assertSame('first', $exception->getName());
            $this->assertSame($exception->getMessage(), 'Value for parameter "first" can not be found in route' .
                ' match parameters or request attributes and has no default value or allows null.');
        }
    }
}
