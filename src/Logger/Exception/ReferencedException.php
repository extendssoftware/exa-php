<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Exception;

use Exception;
use Throwable;

class ReferencedException extends Exception implements ReferencedExceptionInterface
{
    /**
     * LoggedException constructor.
     *
     * @param Throwable $throwable
     * @param string    $reference
     */
    public function __construct(Throwable $throwable, private readonly string $reference)
    {
        parent::__construct($throwable->getMessage(), $throwable->getCode(), $throwable);
    }

    /**
     * @inheritDoc
     */
    public function getReference(): string
    {
        return $this->reference;
    }
}
