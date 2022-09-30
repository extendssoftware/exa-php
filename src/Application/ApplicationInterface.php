<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application;

interface ApplicationInterface
{
    /**
     * Bootstrap application.
     *
     * @throws ApplicationException
     */
    public function bootstrap(): void;
}
