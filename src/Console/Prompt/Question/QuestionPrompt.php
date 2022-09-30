<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Prompt\Question;

use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use ExtendsSoftware\ExaPHP\Console\Prompt\PromptInterface;

class QuestionPrompt implements PromptInterface
{
    /**
     * Question to get answer for.
     *
     * @var string
     */
    private string $question;

    /**
     * If an answer is required.
     *
     * @var bool
     */
    private bool $required;

    /**
     * Create new question prompt.
     *
     * @param string    $question
     * @param bool|null $required
     */
    public function __construct(string $question, bool $required = null)
    {
        $this->question = $question;
        $this->required = $required ?? true;
    }

    /**
     * @inheritDoc
     */
    public function prompt(InputInterface $input, OutputInterface $output): ?string
    {
        do {
            $output->text($this->question . ': ');
            $answer = $input->line();
        } while ($this->required && $answer === null);

        return $answer;
    }
}
