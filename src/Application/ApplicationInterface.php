<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application;

use ExtendsSoftware\ExaPHP\Application\Module\ModuleInterface;

interface ApplicationInterface
{
    /**
     * Bootstrap application.
     *
     * @throws ApplicationException
     */
    public function bootstrap(): void;

    /**
     * Get initialized modules.
     *
     * @return ModuleInterface[]
     */
    public function getModules(): array;
}
