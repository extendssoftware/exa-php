<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolverException;

class InvalidFactoryType extends Exception implements FactoryResolverException
{
    /**
     * Invalid type for factory.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct(sprintf(
            'Factory must be a subclass of ServiceFactoryInterface, got "%s".',
            $type
        ));
    }
}
