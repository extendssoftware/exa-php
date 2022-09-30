<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Role\RoleInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationInfoTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that correct values will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\AuthorizationInfo::addPermission()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\AuthorizationInfo::addRole()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\AuthorizationInfo::getPermissions()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\AuthorizationInfo::getRoles()
     */
    public function testGetMethods(): void
    {
        $permission = $this->createMock(PermissionInterface::class);

        $role = $this->createMock(RoleInterface::class);

        /**
         * @var PermissionInterface $permission
         * @var RoleInterface       $role
         */
        $info = new AuthorizationInfo();
        $info
            ->addPermission($permission)
            ->addPermission($permission)
            ->addRole($role)
            ->addRole($role);

        $this->assertSame([
            $permission,
            $permission,
        ], $info->getPermissions());
        $this->assertSame([
            $role,
            $role,
        ], $info->getRoles());
    }
}
