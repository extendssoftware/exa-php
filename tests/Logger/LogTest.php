<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use PHPUnit\Framework\TestCase;
use Throwable;

class LogTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getMessage()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getPriority()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getDateTime()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getMetaData()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getThrowable()
     */
    public function testGetMethods(): void
    {
        $log = new Log('Error!');

        $this->assertSame('Error!', $log->getMessage());
        $this->assertIsObject($log->getPriority());
        $this->assertIsObject($log->getDateTime());
        $this->assertNull($log->getThrowable());
        $this->assertSame([], $log->getMetaData());
    }

    /**
     * With methods.
     *
     * Test that methods will change the message, metadata and return a new instance.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::withMessage()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::withMetaData()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::withThrowable()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getMessage()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getMetaData()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getThrowable()
     */
    public function testWithMethods(): void
    {
        $throwable = $this->createMock(Throwable::class);

        $log = new Log('Error!', metaData: [
            'foo' => 'bar',
        ]);

        $instance = $log
            ->withMessage('Exception!')
            ->withMetaData([
                'bar' => 'baz',
            ])
            ->withThrowable($throwable);

        $this->assertNotSame($log, $instance);
        $this->assertIsObject($instance);
        $this->assertSame('Exception!', $instance->getMessage());
        $this->assertSame($throwable, $instance->getThrowable());
        $this->assertSame(['bar' => 'baz'], $instance->getMetaData());
    }

    /**
     * And methods.
     *
     * Test that methods will change the message, metadata and return a new instance.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::andMetaData()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Log::getMetaData()
     */
    public function testAndMethods(): void
    {
        $log = new Log('Error!', metaData: [
            'foo' => 'bar',
        ]);
        $instance = $log->andMetaData('bar', 'baz');

        $this->assertNotSame($log, $instance);
        $this->assertIsObject($instance);
        $this->assertSame(['foo' => 'bar', 'bar' => 'baz'], $instance->getMetaData());
    }
}
