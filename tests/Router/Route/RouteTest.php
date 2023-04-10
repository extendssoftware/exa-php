<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
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
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getValidators()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getParameters()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Route::getName()
     */
    public function testGetMethods(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);

        /**
         * @var ValidatorInterface $validator
         */
        $route = new Route(
            '/',
            Method::POST,
            [
                'parameter' => $validator,
            ],
            [
                'foo' => 'bar',
            ],
            'index'
        );

        $this->assertSame('/', $route->getPath());
        $this->assertSame(Method::POST, $route->getMethod());
        $this->assertSame(['parameter' => $validator], $route->getValidators());
        $this->assertSame(['foo' => 'bar'], $route->getParameters());
        $this->assertSame('index', $route->getName());
    }
}
