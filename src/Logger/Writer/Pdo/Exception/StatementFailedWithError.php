<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Writer\Pdo\Exception;

use ExtendsSoftware\ExaPHP\Logger\Writer\Pdo\PdoWriterException;
use RuntimeException;

class StatementFailedWithError extends RuntimeException implements PdoWriterException
{
    /**
     * StatementFailedWithError constructor.
     *
     * @param string $error
     * @param string $message
     */
    public function __construct(string $error, string $message)
    {
        parent::__construct(
            sprintf(
                'Failed to write message "%s" to PDO, got error code "%s"',
                $message,
                $error
            )
        );
    }
}
