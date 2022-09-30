<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Yellow\Yellow;
use ExtendsSoftware\ExaPHP\Console\Formatter\FormatterException;
use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use ExtendsSoftware\ExaPHP\Console\Prompt\PromptInterface;

class MultipleChoicePrompt implements PromptInterface
{
    /**
     * Question to get option for.
     *
     * @var string
     */
    private string $question;

    /**
     * Valid options to answer for question.
     *
     * @var mixed[]
     */
    private array $options;

    /**
     * If an answer is required.
     *
     * @var bool
     */
    private bool $required;

    /**
     * Create new multiple choice prompt.
     *
     * @param string    $question
     * @param mixed[]   $options
     * @param bool|null $required
     */
    public function __construct(string $question, array $options, bool $required = null)
    {
        $this->question = $question;
        $this->options = $options;
        $this->required = $required ?? true;
    }

    /**
     * @inheritDoc
     * @throws FormatterException
     */
    public function prompt(InputInterface $input, OutputInterface $output): ?string
    {
        do {
            $output
                ->text($this->question . ' ')
                ->text(
                    sprintf('[%s]', implode(',', $this->options)),
                    $output
                        ->getFormatter()
                        ->setForeground(new Yellow())
                )
                ->text(': ');

            $option = $input->character();
        } while (!in_array($option, $this->options, true) && $this->required && $option === null);

        return $option;
    }
}
