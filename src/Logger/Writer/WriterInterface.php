<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Writer;

use ExtendsSoftware\ExaPHP\Logger\LogInterface;

interface WriterInterface
{
    /**
     * Write log.
     *
     * @param LogInterface $log
     *
     * @return WriterInterface
     * @throws WriterException
     */
    public function write(LogInterface $log): WriterInterface;
}
