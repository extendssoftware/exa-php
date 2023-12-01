<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json;

use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Hateoas\ResourceInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Serializer\SerializerInterface;
use ExtendsSoftware\ExaPHP\Router\RouterException;

class JsonSerializer implements SerializerInterface
{
    /**
     * @inheritDoc
     * @throws RouterException
     */
    public function serialize(ResourceInterface $resource): string
    {
        return json_encode(
            $this->toArray($resource)
        ) ?: '';
    }

    /**
     * Serialize resource to array.
     *
     * @param ResourceInterface $resource
     *
     * @return mixed[]
     * @throws RouterException
     */
    private function toArray(ResourceInterface $resource): array
    {
        return array_merge(
            array_filter(
                [
                    '_links' => $this->serializeLinks($resource->getLinks()),
                    '_embedded' => $this->serializeResources($resource->getResources()),
                ]
            ),
            $this->serializeAttributes($resource->getAttributes())
        );
    }

    /**
     * Serialize links.
     *
     * @param LinkInterface[]|LinkInterface[][] $links
     *
     * @return mixed[]
     * @throws RouterException
     */
    private function serializeLinks(array $links): array
    {
        $serialized = [];
        foreach ($links as $rel => $link) {
            if (is_array($link)) {
                $serialized[$rel] = $this->serializeLinks($link);
            } else {
                $serialized[$rel] = [
                    'href' => $link
                        ->getUri()
                        ->toRelative(),
                ];
            }
        }

        return $serialized;
    }

    /**
     * Serialize embedded resources.
     *
     * @param ResourceInterface[]|ResourceInterface[][] $resources
     *
     * @return mixed[]
     * @throws RouterException
     */
    private function serializeResources(array $resources): array
    {
        $serialized = [];
        foreach ($resources as $rel => $resource) {
            if (is_array($resource)) {
                $serialized[$rel] = array_values($this->serializeResources($resource));
            } else {
                $serialized[$rel] = $this->toArray($resource);
            }
        }

        return $serialized;
    }

    /**
     * Serialize attributes.
     *
     * @param AttributeInterface[] $attributes
     *
     * @return mixed[]
     */
    private function serializeAttributes(array $attributes): array
    {
        $serialized = [];
        foreach ($attributes as $property => $attribute) {
            $serialized[$property] = $attribute->getValue();
        }

        return $serialized;
    }
}
