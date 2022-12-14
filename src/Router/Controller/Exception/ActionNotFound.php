<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Controller\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Router\Controller\ControllerException;

class ActionNotFound extends Exception implements ControllerException
{
    /**
     * When action is missing in request.
     */
    public function __construct()
    {
        parent::__construct('No controller action was found in request.');
    }
}
