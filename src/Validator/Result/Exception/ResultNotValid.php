<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Validator\ValidatorException;

class ResultNotValid extends Exception implements ValidatorException
{
    /**
     * ResultNotValid constructor.
     */
    public function __construct()
    {
        parent::__construct('Can not get value from an invalid result.');
    }
}
