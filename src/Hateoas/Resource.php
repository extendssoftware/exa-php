<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas;

use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;

class Resource implements ResourceInterface
{
    /**
     * Links.
     *
     * @var LinkInterface[]|LinkInterface[][]
     */
    private array $links;

    /**
     * Attributes.
     *
     * @var AttributeInterface[]
     */
    private array $attributes;

    /**
     * Embedded resources.
     *
     * @var ResourceInterface[]|ResourceInterface[][]
     */
    private array $resources;

    /**
     * Resource constructor.
     *
     * @param LinkInterface[]|LinkInterface[][]              $links
     * @param AttributeInterface[]                           $attributes
     * @param ResourceInterface[]|ResourceInterface[][]|null $resources
     */
    public function __construct(array $links, array $attributes, array $resources = null)
    {
        $this->links = $links;
        $this->attributes = $attributes;
        $this->resources = $resources ?? [];
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
