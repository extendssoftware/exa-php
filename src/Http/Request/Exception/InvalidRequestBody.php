<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Request\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Http\Request\RequestException;

class InvalidRequestBody extends Exception implements RequestException
{
    /**
     * When request body is invalid JSON with error.
     *
     * @param string $error
     */
    public function __construct(string $error)
    {
        parent::__construct(
            sprintf(
                'Invalid JSON for request body, got parse error "%s".',
                $error
            )
        );
    }
}
