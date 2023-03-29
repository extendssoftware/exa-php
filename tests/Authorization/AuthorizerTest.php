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
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::isPermitted()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::getPermissions()
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
            ->expects($this->once()) // Use cached permissions for identity.
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

        $this->assertTrue($authorizer->isPermitted($permission, $identity));
        $this->assertFalse($authorizer->isPermitted($permission, $identity));
    }

    /**
     * No permissions.
     *
     * Test that identity without permissions is not permitted.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::isPermitted()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::getPermissions()
     */
    public function testNoPermissions(): void
    {
        $permission = $this->createMock(PermissionInterface::class);
        $permission
            ->expects($this->never())
            ->method('implies');

        $identity = $this->createMock(IdentityInterface::class);

        /**
         * @var PermissionInterface $permission
         * @var IdentityInterface   $identity
         */
        $authorizer = new Authorizer();

        $this->assertFalse($authorizer->isPermitted($permission, $identity));
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
            ->with($this->isInstanceOf(AuthorizerInterface::class), $identity)
            ->willReturn(true);

        /**
         * @var IdentityInterface $identity
         * @var PolicyInterface   $policy
         */
        $authorizer = new Authorizer();
        $isAllowed = $authorizer->isAllowed($policy, $identity);

        $this->assertTrue($isAllowed);
    }

    /**
     * Not allowed by default.
     *
     * Test that permission is not allowed by default.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Authorizer::isAllowed()
     */
    public function testNotAllowedByDefault(): void
    {
        $permission = $this->createMock(PermissionInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        /**
         * @var IdentityInterface $identity
         * @var PolicyInterface   $policy
         */
        $authorizer = new Authorizer();

        $this->assertSame(false, $authorizer->isPermitted($permission, $identity));
    }
}
