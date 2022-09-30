<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use PHPUnit\Framework\TestCase;

class LoggerWriterTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::getWriter()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::mustInterrupt()
     */
    public function testGetMethods(): void
    {
        $writer = $this->createMock(WriterInterface::class);

        /**
         * @var WriterInterface $writer
         */
        $loggerWriter = new LoggerWriter($writer, true);

        $this->assertSame($writer, $loggerWriter->getWriter());
        $this->assertTrue($loggerWriter->mustInterrupt());
    }
}
