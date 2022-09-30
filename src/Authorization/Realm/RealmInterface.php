<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Realm;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizationInfoInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface RealmInterface
{
    /**
     * Get authorization information for identity.
     *
     * @param IdentityInterface $identity
     *
     * @return AuthorizationInfoInterface|null
     */
    public function getAuthorizationInfo(IdentityInterface $identity): ?AuthorizationInfoInterface;
}
