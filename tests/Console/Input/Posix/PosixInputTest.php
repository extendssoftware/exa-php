<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Input\Posix;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TypeError;

class PosixInputTest extends TestCase
{
    /**
     * Line.
     *
     * Test that line ('Hello world! How are you doing?') can be read from input and is returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::line()
     */
    public function testLine(): void
    {
        $root = vfsStream::setup();
        file_put_contents($root->url() . '/posix', 'Hello world! How are you doing?');

        $input = new PosixInput(fopen($root->url() . '/posix', 'r'));
        $line = $input->line();

        $this->assertEquals('Hello world! How are you doing?', $line);
    }

    /**
     * Line with length.
     *
     * Test that line ('Hello world!  How are you doing?') with max length (13) can be read from input and is returned
     * as shortened text ('Hello world!').
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::line()
     */
    public function testLineWithLength(): void
    {
        $root = vfsStream::setup();
        file_put_contents($root->url() . '/posix', 'Hello world! How are you doing?');

        $input = new PosixInput(fopen($root->url() . '/posix', 'r'));
        $line = $input->line(13);

        $this->assertEquals('Hello world!', $line);
    }

    /**
     * Line with return.
     *
     * Test that null will be returned on newline.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::line()
     */
    public function testLineWithReturn(): void
    {
        $root = vfsStream::setup();
        file_put_contents($root->url() . '/posix', "\n\r");

        $input = new PosixInput(fopen($root->url() . '/posix', 'r'));
        $character = $input->line();

        $this->assertNull($character);
    }

    /**
     * Character.
     *
     * Test that character ('b') can be read from input and is returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::character()
     */
    public function testCharacter(): void
    {
        $root = vfsStream::setup();
        file_put_contents($root->url() . '/posix', 'b');

        $input = new PosixInput(fopen($root->url() . '/posix', 'r'));
        $character = $input->character();

        $this->assertEquals('b', $character);
    }

    /**
     * Character with return.
     *
     * Test that null will be returned on newline.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::character()
     */
    public function testCharacterWithReturn(): void
    {
        $root = vfsStream::setup();
        file_put_contents($root->url() . '/posix', "\r\n");

        $input = new PosixInput(fopen($root->url() . '/posix', 'r'));
        $character = $input->character();

        $this->assertNull($character);
    }

    /**
     * Allowed character.
     *
     * Test that only the allowed character ('a') is read and ('b') is ignored.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::character()
     */
    public function testAllowedCharacter(): void
    {
        $root = vfsStream::setup();
        file_put_contents($root->url() . '/posix', 'aa');

        $input = new PosixInput(fopen($root->url() . '/posix', 'r'));

        $first = $input->character('b');
        $second = $input->character('a');

        $this->assertNull($first);
        $this->assertEquals('a', $second);
    }

    /**
     * Stream not resource.
     *
     * Test that an exception will be thrown when filename can not be opened for reading.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput::__construct()
     */
    public function testStreamNotResource(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Stream must be of type resource, string given.');

        new PosixInput('foo');
    }
}
