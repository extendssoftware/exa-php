<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Module\Provider;

use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

interface ConfigProviderInterface
{
    /**
     * Get module config.
     *
     * @return LoaderInterface
     */
    public function getConfig(): LoaderInterface;
}
