<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Result\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Processor\ProcessorException;

class ResultNotValid extends Exception implements ProcessorException
{
    /**
     * ResultNotValid constructor.
     */
    public function __construct()
    {
        parent::__construct('Can not get value from an invalid result.');
    }
}
