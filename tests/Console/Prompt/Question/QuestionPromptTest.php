<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Prompt\Question;

use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use PHPUnit\Framework\TestCase;

class QuestionPromptTest extends TestCase
{
    /**
     * Prompt.
     *
     * Test that question prompt ("How are you doing?") will be prompted ("How are you doing?: ").
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\Question\QuestionPrompt::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\Question\QuestionPrompt::prompt()
     */
    public function testCanPromptQuestion(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->once())
            ->method('line')
            ->willReturn('Very good!');

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('text')
            ->with('How are you doing?: ')
            ->willReturnSelf();

        /**
         * @var InputInterface  $input
         * @var OutputInterface $output
         */
        $question = new QuestionPrompt('How are you doing?');
        $answer = $question->prompt($input, $output);

        $this->assertSame('Very good!', $answer);
    }

    /**
     * Required.
     *
     * Test that prompt will show again after not allowed answer (null) until valid answer ("Very good!").
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\Question\QuestionPrompt::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\Question\QuestionPrompt::prompt()
     */
    public function testRequired(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->exactly(2))
            ->method('line')
            ->willReturnOnConsecutiveCalls(null, 'Very good!');

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->exactly(2))
            ->method('text')
            ->willReturnCallback(fn($text) => match ([$text]) {
                ['How are you doing?: '] => $output,
            });

        /**
         * @var InputInterface  $input
         * @var OutputInterface $output
         */
        $question = new QuestionPrompt('How are you doing?');
        $answer = $question->prompt($input, $output);

        $this->assertSame('Very good!', $answer);
    }

    /**
     * Not required.
     *
     * Test that prompt answer can be skipped (null) when not required.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\Question\QuestionPrompt::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Console\Prompt\Question\QuestionPrompt::prompt()
     */
    public function testNotRequired(): void
    {
        $input = $this->createMock(InputInterface::class);
        $input
            ->expects($this->once())
            ->method('line')
            ->willReturn(null);

        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('text')
            ->with('How are you doing?: ')
            ->willReturnSelf();

        /**
         * @var InputInterface  $input
         * @var OutputInterface $output
         */
        $question = new QuestionPrompt('How are you doing?', false);
        $answer = $question->prompt($input, $output);

        $this->assertNull($answer);
    }
}
