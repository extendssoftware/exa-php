<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas;

use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;

readonly class Resource implements ResourceInterface
{
    /**
     * Resource constructor.
     *
     * @param LinkInterface[]|LinkInterface[][]         $links
     * @param AttributeInterface[]                      $attributes
     * @param ResourceInterface[]|ResourceInterface[][] $resources
     */
    public function __construct(private array $links, private array $attributes, private array $resources = [])
    {
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
