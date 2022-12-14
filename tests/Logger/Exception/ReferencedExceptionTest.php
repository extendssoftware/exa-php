<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Exception;

use Exception;
use PHPUnit\Framework\TestCase;

class ReferencedExceptionTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Exception\ReferencedException::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Exception\ReferencedException::getReference()
     */
    public function testGetters(): void
    {
        $throwable = new Exception('Foo bar!', 123);

        $exception = new ReferencedException($throwable, 'foobar');

        $this->assertSame('Foo bar!', $exception->getMessage());
        $this->assertSame(123, $exception->getCode());
        $this->assertSame($throwable, $exception->getPrevious());
        $this->assertSame('foobar', $exception->getReference());
    }
}
