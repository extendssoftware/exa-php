<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Method\Exception;

use PHPUnit\Framework\TestCase;

class MethodNotAllowedTest extends TestCase
{
    /**
     * Get allowed methods.
     *
     * Test that correct allowed methods will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed::getMethod()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed::getAllowedMethods()
     */
    public function testGetAllowedMethods(): void
    {
        $exception = new MethodNotAllowed('GET', ['POST', 'PUT']);

        $this->assertSame('GET', $exception->getMethod());
        $this->assertSame([
            'POST',
            'PUT',
        ], $exception->getAllowedMethods());
    }

    /**
     * Get allowed methods.
     *
     * Test that correct allowed methods will be added.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed::addAllowedMethods()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed::getAllowedMethods()
     */
    public function testAddAllowedMethods(): void
    {
        $exception = new MethodNotAllowed('GET', ['POST', 'PUT']);
        $exception->addAllowedMethods(['PUT', 'DELETE', 'TRACE']);

        $this->assertSame([
            'POST',
            'PUT',
            'DELETE',
            'TRACE',
        ], $exception->getAllowedMethods());
    }
}
