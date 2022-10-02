<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Output\Posix;

use ExtendsSoftware\ExaPHP\Console\Formatter\Ansi\AnsiFormatter;
use ExtendsSoftware\ExaPHP\Console\Formatter\FormatterInterface;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use TypeError;

class PosixOutput implements OutputInterface
{
    /**
     * PosixOutput constructor.
     *
     * @param FormatterInterface $formatter
     * @param int                $verbosity
     * @param mixed              $stream
     *
     * @throws TypeError When stream not of type resource.
     */
    public function __construct(
        private readonly FormatterInterface $formatter = new AnsiFormatter(),
        private int                         $verbosity = 1,
        private mixed                       $stream = null
    ) {
        $stream = $stream ?: fopen('php://output', 'w');
        if (!is_resource($stream)) {
            throw new TypeError(sprintf(
                'Stream must be of type resource, %s given.',
                gettype($stream)
            ));
        }

        $this->stream = $stream;
    }

    /**
     * @inheritDoc
     */
    public function text(string $text, FormatterInterface $formatter = null, int $verbosity = null): OutputInterface
    {
        if (($verbosity ?? 1) <= $this->verbosity) {
            if ($formatter) {
                $text = $formatter->create($text);
            }

            fwrite($this->stream, $text);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function line(string $text, FormatterInterface $formatter = null, int $verbosity = null): OutputInterface
    {
        return $this
            ->text($text, $formatter, $verbosity)
            ->newLine($verbosity);
    }

    /**
     * @inheritDoc
     */
    public function newLine(int $verbosity = null): OutputInterface
    {
        return $this->text("\n\r", verbosity: $verbosity);
    }

    /**
     * @inheritDoc
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * @inheritDoc
     */
    public function setVerbosity(int $verbosity): OutputInterface
    {
        $this->verbosity = $verbosity;

        return $this;
    }
}
