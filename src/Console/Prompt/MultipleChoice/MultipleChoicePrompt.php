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
     * Create new multiple choice prompt.
     *
     * @param string $question
     * @param mixed[] $options
     * @param bool $required
     */
    public function __construct(
        private readonly string $question,
        private readonly array  $options,
        private readonly bool   $required = true
    ) {
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
                    sprintf(
                        '[%s]',
                        implode(',', $this->options)
                    ),
                    $output
                        ->getFormatter()
                        ->setForeground(new Yellow())
                )
                ->text(': ');

            $option = $input->character();
        } while (($this->required && $option === null) ||
            ($option !== null && !in_array($option, $this->options, true)));

        return $option;
    }
}
