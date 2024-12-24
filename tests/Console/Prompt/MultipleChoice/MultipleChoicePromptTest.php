<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice;

use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use PHPUnit\Framework\TestCase;

class MultipleChoicePromptTest extends TestCase
{
    /**
     * Prompt.
     *
     * Test that multiple choice prompt ("Continue?" with option "y" and "n") will be prompted ("Continue? [y,n]: ").
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::prompt()
     */
    public function testPrompt(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->once())
            ->method('character')
            ->willReturn('y');

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->exactly(3))
            ->method('text')
            ->willReturnCallback(fn($text) => match ([$text]) {
                ['Continue? '],
                ['[y,n]'],
                [': '] => $output,
            });

        /**
         * @var InputInterface  $input
         * @var OutputInterface $output
         */
        $multipleChoice = new MultipleChoicePrompt('Continue?', ['y', 'n']);
        $continue = $multipleChoice->prompt($input, $output);

        $this->assertSame('y', $continue);
    }

    /**
     * Required.
     *
     * Test that prompt will show again after not allowed answer (null) until valid answer ("y").
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::prompt()
     */
    public function testRequired(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->exactly(3))
            ->method('character')
            ->willReturnOnConsecutiveCalls(null, 'x', 'y');

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->exactly(9))
            ->method('text')
            ->willReturnCallback(fn($text) => match ([$text]) {
                ['Continue? '],
                ['[y,n]'],
                [': '] => $output,
            });

        /**
         * @var InputInterface  $input
         * @var OutputInterface $output
         */
        $multipleChoice = new MultipleChoicePrompt('Continue?', ['y', 'n']);
        $continue = $multipleChoice->prompt($input, $output);

        $this->assertSame('y', $continue);
    }

    /**
     * Not required.
     *
     * Test that prompt answer can be skipped (null) when not required.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::prompt()
     */
    public function testNotRequired(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->exactly(2))
            ->method('character')
            ->willReturnOnConsecutiveCalls('x', null);

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->exactly(6))
            ->method('text')
            ->willReturnCallback(fn($text) => match ([$text]) {
                ['Continue? '],
                ['[y,n]'],
                [': '] => $output,
            })
            ->willReturnSelf();

        /**
         * @var InputInterface  $input
         * @var OutputInterface $output
         */
        $multipleChoice = new MultipleChoicePrompt('Continue?', ['y', 'n'], false);
        $continue = $multipleChoice->prompt($input, $output);

        $this->assertNull($continue);
    }

    /**
     * Default option.
     *
     * Test that default option will be shown capitalized and will return on empty input.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice\MultipleChoicePrompt::prompt()
     */
    public function testDefaultOption(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->exactly(1))
            ->method('character')
            ->willReturnOnConsecutiveCalls(null);

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->exactly(3))
            ->method('text')
            ->willReturnCallback(fn($text) => match ([$text]) {
                ['Continue? '],
                ['[y,N]'],
                [': '] => $output,
            })
            ->willReturnSelf();

        /**
         * @var InputInterface  $input
         * @var OutputInterface $output
         */
        $multipleChoice = new MultipleChoicePrompt('Continue?', ['y', 'n'], false, 'n');
        $continue = $multipleChoice->prompt($input, $output);

        $this->assertSame('n', $continue);
    }
}
