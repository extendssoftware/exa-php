<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Prompt\MultipleChoice;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Yellow\Yellow;
use ExtendsSoftware\ExaPHP\Console\Formatter\FormatterException;
use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use ExtendsSoftware\ExaPHP\Console\Prompt\PromptInterface;

readonly class MultipleChoicePrompt implements PromptInterface
{
    /**
     * Create new multiple choice prompt.
     *
     * @param string      $question
     * @param string[]    $options
     * @param bool        $required
     * @param string|null $default
     */
    public function __construct(
        private string $question,
        private array $options,
        private bool $required = true,
        private ?string $default = null
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
                        implode(
                            ',',
                            array_map(function (string $option) {
                                if (is_string($this->default) && $option === $this->default) {
                                    return strtoupper($option);
                                }

                                return $option;
                            }, $this->options)
                        )
                    ),
                    $output
                        ->getFormatter()
                        ->setForeground(new Yellow())
                )
                ->text(': ');

            $option = $input->character();
            if ($option === null && is_string($this->default)) {
                $option = $this->default;
            }
        } while (($this->required && $option === null) ||
        ($option !== null && !in_array($option, $this->options, true)));

        return $option;
    }
}
