<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Serializer;

use ExtendsSoftware\ExaPHP\Hateoas\ResourceInterface;

interface SerializerInterface
{
    /**
     * Serialize resource.
     *
     * @param ResourceInterface $resource
     *
     * @return string
     */
    public function serialize(ResourceInterface $resource): string;
}
