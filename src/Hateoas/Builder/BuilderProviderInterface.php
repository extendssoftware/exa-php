<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder;

use ExtendsSoftware\ExaPHP\Router\RouterInterface;

interface BuilderProviderInterface
{
    /**
     * Get builder.
     *
     * @param RouterInterface $router
     *
     * @return BuilderInterface
     */
    public function getBuilder(RouterInterface $router): BuilderInterface;
}
