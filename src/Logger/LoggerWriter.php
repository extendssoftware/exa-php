<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;

readonly class LoggerWriter
{
    /**
     * Create new logger writer.
     *
     * @param WriterInterface $writer
     * @param bool            $interrupt
     */
    public function __construct(private WriterInterface $writer, private bool $interrupt)
    {
    }

    /**
     * Get writer.
     *
     * @return WriterInterface
     */
    public function getWriter(): WriterInterface
    {
        return $this->writer;
    }

    /**
     * If logger must interrupt after writer.
     *
     * @return bool
     */
    public function mustInterrupt(): bool
    {
        return $this->interrupt;
    }
}
