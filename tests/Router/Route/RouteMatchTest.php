<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

use PHPUnit\Framework\TestCase;

class RouteMatchTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::getParameters()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::getParameter()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::getPathOffset()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::getName()
     */
    public function testGetMethods(): void
    {
        $match = new RouteMatch(['foo' => 'bar'], 15, 'index');

        $this->assertSame(['foo' => 'bar'], $match->getParameters());
        $this->assertSame('bar', $match->getParameter('foo'));
        $this->assertSame('qux', $match->getParameter('bar', 'qux'));
        $this->assertSame(15, $match->getPathOffset());
        $this->assertSame('index', $match->getName());
    }

    /**
     * Merge.
     *
     * Test that two RouteMatchInterface instances can merge in a third.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::merge()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::getParameters()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\RouteMatch::getPathOffset()
     */
    public function testMerge(): void
    {
        $match1 = new RouteMatch([
            'foo' => 'bar',
        ], 10, 'index');
        $match2 = new RouteMatch([
            'baz' => 'qux',
        ], 15, 'index');
        $match3 = $match1->merge($match2);

        $this->assertSame([
            'foo' => 'bar',
            'baz' => 'qux',
        ], $match3->getParameters());
        $this->assertSame(15, $match3->getPathOffset());
        $this->assertSame('index/index', $match3->getName());
    }
}
