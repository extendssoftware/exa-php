<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Storage\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageException;

class IdentityNotSet extends Exception implements StorageException
{
    /**
     * IdentityNotSet constructor.
     */
    public function __construct()
    {
        parent::__construct('Identity not set.');
    }
}
