<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall;

use ExtendsSoftware\ExaPHP\Firewall\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use PHPUnit\Framework\TestCase;

class FirewallTest extends TestCase
{
    /**
     * Is allowed.
     *
     * Test that request can be verified with realm and request is allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Firewall::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Firewall::isAllowed()
     */
    public function testIsAllowed(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->exactly(2))
            ->method('canVerify')
            ->with($request)
            ->willReturnOnConsecutiveCalls(false, true);

        $realm
            ->expects($this->once())
            ->method('isAllowed')
            ->with($request)
            ->willReturn(true);

        /**
         * @var RealmInterface   $realm
         * @var RequestInterface $request
         */
        $firewall = new Firewall();
        $firewall
            ->addRealm($realm)
            ->addRealm($realm);

        $this->assertTrue($firewall->isAllowed($request));
    }

    /**
     * Is not allowed.
     *
     * Test that request can be verified with realm and request is not allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Firewall::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Firewall::isAllowed()
     */
    public function testIsNotAllowed(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('canVerify')
            ->with($request)
            ->willReturn(true);

        $realm
            ->expects($this->once())
            ->method('isAllowed')
            ->with($request)
            ->willReturn(false);

        /**
         * @var RealmInterface   $realm
         * @var RequestInterface $request
         */
        $firewall = new Firewall();
        $firewall->addRealm($realm);

        $this->assertFalse($firewall->isAllowed($request));
    }
}
