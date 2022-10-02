<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas;

use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;

class Resource implements ResourceInterface
{
    /**
     * Resource constructor.
     *
     * @param LinkInterface[]|LinkInterface[][]         $links
     * @param AttributeInterface[]                      $attributes
     * @param ResourceInterface[]|ResourceInterface[][] $resources
     */
    public function __construct(
        private readonly array $links,
        private readonly array $attributes,
        private readonly array $resources = []
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getResources(): array
    {
        return $this->resources;
    }
}
