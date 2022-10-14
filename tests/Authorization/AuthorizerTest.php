<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Authorization\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;

class AuthorizerTest extends TestCase
{
    /**
     * Is permitted.
     *
     * Test that permission is permitted for identity.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::getAuthorizationInfo()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::isPermitted()
     */
    public function testIsPermitted(): void
    {
        $permission = $this->createMock(PermissionInterface::class);
        $permission
            ->expects($this->exactly(2))
            ->method('implies')
            ->with($permission)
            ->willReturnOnConsecutiveCalls(true, false);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->exactly(2))
            ->method('getPermissions')
            ->with($identity)
            ->willReturn([
                $permission,
            ]);

        /**
         * @var RealmInterface      $realm
         * @var IdentityInterface   $identity
         * @var PermissionInterface $permission
         */
        $authorizer = new Authorizer();
        $authorizer->addRealm($realm);

        $permitted = $authorizer->isPermitted($identity, $permission);

        $this->assertTrue($permitted);

        $permitted = $authorizer->isPermitted($identity, $permission);

        $this->assertFalse($permitted);
    }

    /**
     * Is allowed.
     *
     * Test that identity is allowed by policy.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::isAllowed()
     */
    public function testIsAllowed(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        $policy = $this->createMock(PolicyInterface::class);
        $policy
            ->expects($this->once())
            ->method('isAllowed')
            ->with($identity, $this->isInstanceOf(AuthorizerInterface::class))
            ->willReturn(true);

        /**
         * @var IdentityInterface $identity
         * @var PolicyInterface   $policy
         */
        $authorizer = new Authorizer();
        $isAllowed = $authorizer->isAllowed($identity, $policy);

        $this->assertTrue($isAllowed);
    }
}
