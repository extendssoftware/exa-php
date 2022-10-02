<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;

class LoggerWriter
{
    /**
     * Create new logger writer.
     *
     * @param WriterInterface $writer
     * @param bool            $interrupt
     */
    public function __construct(private readonly WriterInterface $writer, private readonly bool $interrupt)
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
