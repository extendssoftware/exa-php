<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Link;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;

readonly class Link implements LinkInterface
{
    /**
     * Link constructor.
     *
     * @param UriInterface             $uri
     * @param bool                     $embeddable
     * @param PermissionInterface|null $permission
     * @param PolicyInterface|null     $policy
     */
    public function __construct(
        private UriInterface $uri,
        private bool $embeddable = false,
        private ?PermissionInterface $permission = null,
        private ?PolicyInterface $policy = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function isEmbeddable(): bool
    {
        return $this->embeddable;
    }

    /**
     * @inheritDoc
     */
    public function getPermission(): ?PermissionInterface
    {
        return $this->permission;
    }

    /**
     * @inheritDoc
     */
    public function getPolicy(): ?PolicyInterface
    {
        return $this->policy;
    }
}
