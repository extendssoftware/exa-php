<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application;

interface ApplicationBuilderInterface
{
    /**
     * Build application.
     *
     * @return ApplicationInterface
     * @throws ApplicationBuilderException
     */
    public function build(): ApplicationInterface;
}
