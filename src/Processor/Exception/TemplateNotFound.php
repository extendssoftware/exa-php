<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Processor\ProcessorException;

use function sprintf;

class TemplateNotFound extends Exception implements ProcessorException
{
    /**
     * Template not found for key.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        parent::__construct(
            sprintf(
                'No invalid result template found for key "%s".',
                $key
            )
        );
    }
}
