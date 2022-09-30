<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Builder;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Attribute\AttributeInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotEmbeddable;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Hateoas\ResourceInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface BuilderInterface
{
    /**
     * Add link.
     *
     * @param string        $relation
     * @param LinkInterface $link
     * @param bool|null     $singular
     *
     * @return Builder
     */
    public function addLink(string $relation, LinkInterface $link, bool $singular = null): BuilderInterface;

    /**
     * Add attribute.
     *
     * @param string             $property
     * @param AttributeInterface $attribute
     *
     * @return Builder
     */
    public function addAttribute(string $property, AttributeInterface $attribute): BuilderInterface;

    /**
     * Add resource.
     *
     * @param string           $relation
     * @param BuilderInterface $resource
     * @param bool|null        $singular
     *
     * @return Builder
     */
    public function addResource(string $relation, BuilderInterface $resource, bool $singular = null): BuilderInterface;

    /**
     * Set links relations to embed.
     *
     * @param string[]|string[][]|null $relations
     *
     * @return BuilderInterface
     */
    public function setToExpand(array $relations = null): BuilderInterface;

    /**
     * Set properties to project.
     *
     * @param string[]|string[][]|null $properties
     *
     * @return BuilderInterface
     */
    public function setToProject(array $properties = null): BuilderInterface;

    /**
     * Set identity to use for authorization.
     *
     * @param IdentityInterface|null $identity
     *
     * @return BuilderInterface
     */
    public function setIdentity(IdentityInterface $identity = null): BuilderInterface;

    /**
     * Set authorizer.
     *
     * @param AuthorizerInterface|null $authorizer
     *
     * @return BuilderInterface
     */
    public function setAuthorizer(AuthorizerInterface $authorizer = null): BuilderInterface;

    /**
     * Set resource expander.
     *
     * @param ExpanderInterface|null $expander
     *
     * @return BuilderInterface
     */
    public function setExpander(ExpanderInterface $expander = null): BuilderInterface;

    /**
     * Build resource.
     *
     * @return ResourceInterface
     * @throws LinkNotFound
     * @throws LinkNotEmbeddable
     * @throws AttributeNotFound
     */
    public function build(): ResourceInterface;
}
