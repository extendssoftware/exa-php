<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Link;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;

interface LinkInterface
{
    /**
     * Get URI.
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface;

    /**
     * If link is embeddable.
     *
     * @return bool
     */
    public function isEmbeddable(): bool;

    /**
     * Get permission.
     *
     * @return PermissionInterface|null
     */
    public function getPermission(): ?PermissionInterface;

    /**
     * Get policy.
     *
     * @return PolicyInterface|null
     */
    public function getPolicy(): ?PolicyInterface;
}
