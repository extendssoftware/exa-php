<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Link;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    /**
     * Test that getter methods will return the correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Link\Link::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Link\Link::getRequest()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Link\Link::isEmbeddable()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Link\Link::getPermission()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Link\Link::getPolicy()
     */
    public function testGetters(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $permission = $this->createMock(PermissionInterface::class);

        $policy = $this->createMock(PolicyInterface::class);

        /**
         * @var RequestInterface    $request
         * @var PermissionInterface $permission
         * @var PolicyInterface     $policy
         */
        $link = new Link($request, true, $permission, $policy);

        $this->assertTrue($link->isEmbeddable());
        $this->assertSame($request, $link->getRequest());
        $this->assertSame($permission, $link->getPermission());
        $this->assertSame($policy, $link->getPolicy());
    }
}
