<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Attribute;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{

    /**
     * Test that getter methods will return the correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Attribute\Attribute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Attribute\Attribute::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Attribute\Attribute::getPermission()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Attribute\Attribute::getPolicy()
     */
    public function testGetters(): void
    {
        $permission = $this->createMock(PermissionInterface::class);

        $policy = $this->createMock(PolicyInterface::class);

        /**
         * @var PermissionInterface $permission
         * @var PolicyInterface     $policy
         */
        $attribute = new Attribute(1, $permission, $policy);

        $this->assertSame(1, $attribute->getValue());
        $this->assertSame($permission, $attribute->getPermission());
        $this->assertSame($policy, $attribute->getPolicy());
    }
}
