<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Output\Posix;

use ExtendsSoftware\ExaPHP\Console\Formatter\FormatterInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TypeError;

class PosixOutputTest extends TestCase
{
    /**
     * Text.
     *
     * Test that text ('Hello world!') will be sent to output.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::text()
     */
    public function testText(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(stream: fopen($root->url() . '/posix', 'w'));
        $output
            ->setVerbosity(1)
            ->text('Hello world!');

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->assertEquals('Hello world!', $root->getChild('posix')->getContent());
    }

    /**
     * Formatted text.
     *
     * Text that text ('1234567890') with format (fixed with of 5) will be sent to output ('12345').
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::text()
     */
    public function testFormattedText(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(stream: fopen($root->url() . '/posix', 'w'));
        $output
            ->setVerbosity(1)
            ->text('1234567890', $output->getFormatter()->setFixedWidth(5));

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $text = $root->getChild('posix')->getContent();

        $this->assertStringContainsString('12345', $text);
        $this->assertStringNotContainsString('67890', $text);
    }

    /**
     * Line.
     *
     * Test that text ('Hello world!') will be sent to output with newline character.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::line()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::text()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::newLine()
     */
    public function testLine(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(stream: fopen($root->url() . '/posix', 'w'));
        $output
            ->setVerbosity(1)
            ->line('Hello world!');

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->assertEquals('Hello world!' . "\n\r", $root->getChild('posix')->getContent());
    }

    /**
     * New line.
     *
     * Test that new line ("\n\r") will be sent to output.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::newLine()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::text()
     */
    public function testCanWriteNewLineToOutput(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(stream: fopen($root->url() . '/posix', 'w'));
        $output
            ->setVerbosity(1)
            ->newLine();

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->assertEquals("\n\r", $root->getChild('posix')->getContent());
    }

    /**
     * Clear line.
     *
     * Test that clear line ("\r") will be sent to output.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::clearLine()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::text()
     */
    public function testCanWriteClearLineToOutput(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(stream: fopen($root->url() . '/posix', 'w'));
        $output
            ->setVerbosity(1)
            ->clearLine();

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->assertEquals("\r", $root->getChild('posix')->getContent());
    }

    /**
     * Get formatter.
     *
     * Test that the default formatter (AnsiFormatter) will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::getFormatter()
     */
    public function testGetFormatter(): void
    {
        $root = vfsStream::setup();

        $formatter = $this->createMock(FormatterInterface::class);

        $output = new PosixOutput($formatter, stream: fopen($root->url() . '/posix', 'w'));
        $clonedFormatter = $output->getFormatter();

        $this->assertInstanceOf(FormatterInterface::class, $clonedFormatter);
        $this->assertNotSame($formatter, $clonedFormatter);
    }

    /**
     * Higher verbosity.
     *
     * Test that verbosity (3) can be set and still output text with lower verbosity (2).
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::setVerbosity()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::text()
     */
    public function testHigherVerbosity(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(stream: fopen($root->url() . '/posix', 'w'));
        $output
            ->setVerbosity(3)
            ->text('Hello world!', null, 2);

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->assertEquals('Hello world!', $root->getChild('posix')->getContent());
    }

    /**
     * Higher verbosity.
     *
     * Test that verbosity (2) can be set and don't output text with higher verbosity (3).
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::setVerbosity()
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::text()
     */
    public function testLowerVerbosity(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(stream: fopen($root->url() . '/posix', 'w'));
        $output
            ->setVerbosity(2)
            ->text('Hello world!', null, 3);

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->assertEmpty($root->getChild('posix')->getContent());
    }

    /**
     * Stream not resource.
     *
     * Test that an exception will be thrown when filename can not be opened for reading.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput::__construct()
     */
    public function testStreamNotResource(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Stream must be of type resource, string given.');

        new PosixOutput(stream: 'foo');
    }
}
