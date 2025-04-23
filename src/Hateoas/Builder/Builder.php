<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotEmbeddable;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderException;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Resource;
use ExtendsSoftware\ExaPHP\Hateoas\ResourceInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

use function array_filter;
use function array_merge;
use function is_array;

class Builder implements BuilderInterface
{
    /**
     * Authorizer.
     *
     * @var AuthorizerInterface|null
     */
    private ?AuthorizerInterface $authorizer = null;

    /**
     * Resource expander.
     *
     * @var ExpanderInterface|null
     */
    private ?ExpanderInterface $expander = null;

    /**
     * Identity.
     *
     * @var IdentityInterface|null
     */
    private ?IdentityInterface $identity = null;

    /**
     * Links.
     *
     * @var LinkInterface[]|LinkInterface[][]
     */
    private array $links = [];

    /**
     * Attributes.
     *
     * @var AttributeInterface[]
     */
    private array $attributes = [];

    /**
     * Resources.
     *
     * @var BuilderInterface[]|BuilderInterface[][]
     */
    private array $resources = [];

    /**
     * Embeddable link relations to expand in resource.
     *
     * @var mixed[]
     */
    private array $toExpand = [];

    /**
     * Attributes properties to project in resource.
     *
     * @var mixed[]
     */
    private array $toProject = [];

    /**
     * @inheritDoc
     */
    public function addLink(string $relation, LinkInterface $link, bool $singular = null): BuilderInterface
    {
        if ($singular ?? true) {
            $this->links[$relation] = $link;
        } else {
            /** @phpstan-ignore-next-line */
            $this->links[$relation][] = $link;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addAttribute(string $property, AttributeInterface $attribute): BuilderInterface
    {
        $this->attributes[$property] = $attribute;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addResource(string $relation, BuilderInterface $resource, bool $singular = null): BuilderInterface
    {
        if ($singular ?? true) {
            $this->resources[$relation] = $resource;
        } else {
            /** @phpstan-ignore-next-line */
            $this->resources[$relation][] = $resource;
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @throws ExpanderException
     * @throws AttributeNotFound
     * @throws LinkNotFound
     * @throws LinkNotEmbeddable
     */
    public function build(RequestInterface $request): ResourceInterface
    {
        $resource = new Resource(
            $this->getAuthorizedLinks($this->links),
            $this->getProjectedAttributes(
                $this->attributes,
                array_filter($this->toProject, 'is_string'), // Only pass first-level properties.
            ),
            $this->getBuiltResources(
                $request,
                array_merge(
                    $this->resources,
                    $this->getExpandedResources(
                        $this->links,
                        array_filter($this->toExpand, 'is_string'), // Only pass first-level relations.
                        $request,
                    ),
                ),
            ),
        );

        $this->reset();

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function setToProject(array $properties = null): BuilderInterface
    {
        $this->toProject = $properties ?? [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setToExpand(array $relations = null): BuilderInterface
    {
        $this->toExpand = $relations ?? [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setExpander(ExpanderInterface $expander = null): BuilderInterface
    {
        $this->expander = $expander;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setAuthorizer(AuthorizerInterface $authorizer = null): BuilderInterface
    {
        $this->authorizer = $authorizer;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIdentity(IdentityInterface $identity = null): BuilderInterface
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * Get permitted links.
     *
     * @param LinkInterface[]|LinkInterface[][] $links
     *
     * @return mixed[]
     */
    private function getAuthorizedLinks(array $links): array
    {
        $authorized = [];
        foreach ($links as $rel => $link) {
            if (is_array($link)) {
                $authorized[$rel] = $this->getAuthorizedLinks($link);
            } elseif ($this->isAuthorized($link->getPermission(), $link->getPolicy())) {
                $authorized[$rel] = $link;
            }
        }

        return $authorized;
    }

    /**
     * Check if authorized.
     *
     * @param PermissionInterface|null $permission
     * @param PolicyInterface|null     $policy
     *
     * @return bool
     */
    private function isAuthorized(PermissionInterface $permission = null, PolicyInterface $policy = null): bool
    {
        $authorized = true;
        if ($permission || $policy) {
            $authorized = false;

            if ($this->authorizer && $this->identity) {
                if (($permission && $this->authorizer->isPermitted($permission, $this->identity)) ||
                    ($policy && $this->authorizer->isAllowed($policy, $this->identity))
                ) {
                    $authorized = true;
                }
            }
        }

        return $authorized;
    }

    /**
     * Get projected attributes.
     *
     * @param AttributeInterface[] $attributes
     * @param string[]             $properties
     *
     * @return AttributeInterface[]
     * @throws AttributeNotFound
     */
    private function getProjectedAttributes(array $attributes, array $properties): array
    {
        $projected = [];
        foreach ($properties as $property) {
            if (!isset($attributes[$property])) {
                throw new AttributeNotFound($property);
            }

            $attribute = $attributes[$property];
            if ($this->isAuthorized($attribute->getPermission(), $attribute->getPolicy())) {
                $projected[$property] = $attributes[$property];
            }
        }

        return $projected ?: $attributes;
    }

    /**
     * Build built resources.
     *
     * @param RequestInterface                        $request
     * @param BuilderInterface[]|BuilderInterface[][] $resources
     * @param string|null                             $outerRel
     *
     * @return ResourceInterface[]|ResourceInterface[][]
     * @throws AttributeNotFound
     * @throws LinkNotEmbeddable
     * @throws LinkNotFound
     */
    private function getBuiltResources(RequestInterface $request, array $resources, string $outerRel = null): array
    {
        $build = [];
        foreach ($resources as $rel => $resource) {
            if (is_array($resource)) {
                $build[$rel] = $this->getBuiltResources($request, $resource, $rel);
            } else {
                $build[$rel] = $resource
                    ->setIdentity($this->identity)
                    ->setAuthorizer($this->authorizer)
                    ->setExpander($this->expander)
                    ->setToExpand($this->toExpand[$outerRel ?: $rel] ?? [])
                    ->setToProject($this->toProject[$outerRel ?: $rel] ?? [])
                    ->build($request);
            }
        }

        /** @phpstan-ignore-next-line */
        return $build;
    }

    /**
     * Get expanded resources.
     *
     * @param LinkInterface[]|LinkInterface[][] $links
     * @param string[]                          $relations
     * @param RequestInterface                  $request
     *
     * @return BuilderInterface[]|BuilderInterface[][]
     * @throws ExpanderException
     * @throws LinkNotFound
     * @throws LinkNotEmbeddable
     */
    private function getExpandedResources(array $links, array $relations, RequestInterface $request): array
    {
        $expanded = [];
        if ($this->expander) {
            foreach ($relations as $relation) {
                if (!isset($links[$relation])) {
                    throw new LinkNotFound($relation);
                }

                if ($links[$relation] instanceof LinkInterface) {
                    $link = $links[$relation];
                    if (!$link->isEmbeddable()) {
                        throw new LinkNotEmbeddable($relation);
                    }

                    // Only expand the link if permitted by permission and/or policy.
                    if ($this->isAuthorized($link->getPermission(), $link->getPolicy())) {
                        $expanded[$relation] = $this->expander->expand($link, $request);
                    }
                }

                if (is_array($links[$relation])) {
                    foreach ($links[$relation] as $link) {
                        if (!$link->isEmbeddable()) {
                            throw new LinkNotEmbeddable($relation);
                        }

                        if (!isset($expanded[$relation]) || !is_array($expanded[$relation])) {
                            $expanded[$relation] = [];
                        }

                        // Only expand the link if permitted by permission and/or policy.
                        if ($this->isAuthorized($link->getPermission(), $link->getPolicy())) {
                            $expanded[$relation][] = $this->expander->expand($link, $request);
                        }
                    }
                }
            }
        }

        return $expanded;
    }

    /**
     * Reset the builder.
     *
     * @return void
     */
    private function reset(): void
    {
        $this->authorizer = null;
        $this->expander = null;
        $this->identity = null;
        $this->links = [];
        $this->attributes = [];
        $this->resources = [];
        $this->toExpand = [];
        $this->toProject = [];
    }
}
