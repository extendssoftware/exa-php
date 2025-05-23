<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Writer\File\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Logger\Writer\File\FileWriterException;

use function sprintf;

class FileWriterFailed extends Exception implements FileWriterException
{
    /**
     * Failed to write message to filename.
     *
     * @param string $message
     * @param string $filename
     */
    public function __construct(string $message, string $filename)
    {
        parent::__construct(
            sprintf(
                'Failed to write message "%s" to file "%s".',
                $message,
                $filename
            )
        );
    }
}
