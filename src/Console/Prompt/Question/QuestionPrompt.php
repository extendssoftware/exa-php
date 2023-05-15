<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Prompt\Question;

use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use ExtendsSoftware\ExaPHP\Console\Prompt\PromptInterface;

class QuestionPrompt implements PromptInterface
{
    /**
     * Create new question prompt.
     *
     * @param string $question
     * @param bool   $required
     */
    public function __construct(private readonly string $question, private readonly bool $required = true)
    {
    }

    /**
     * @inheritDoc
     */
    public function prompt(InputInterface $input, OutputInterface $output): ?string
    {
        do {
            $output
                ->newLine()
                ->text($this->question . ': ');
            $answer = $input->line();
        } while ($this->required && $answer === null);

        return $answer;
    }
}
