<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas;

use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;

interface ResourceInterface
{
    /**
     * Get links.
     *
     * @return LinkInterface[]|LinkInterface[][]
     */
    public function getLinks(): array;

    /**
     * Get attributes.
     *
     * @return AttributeInterface[]
     */
    public function getAttributes(): array;

    /**
     * Get resources.
     *
     * @return ResourceInterface[]|ResourceInterface[][]
     */
    public function getResources(): array;
}
