<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Writer\Pdo\Exception;

use ExtendsSoftware\ExaPHP\Logger\Writer\Pdo\PdoWriterException;
use PDOException;
use RuntimeException;

class StatementFailedWithException extends RuntimeException implements PdoWriterException
{
    /**
     * StatementFailedWithException constructor.
     *
     * @param PDOException $exception
     * @param string       $message
     */
    public function __construct(PDOException $exception, string $message)
    {
        parent::__construct(
            sprintf(
                'Failed to write message "%s" to PDO. See previous exception for details.',
                $message
            ),
            previous: $exception
        );
    }
}
