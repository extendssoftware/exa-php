<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Algorithm\AlgorithmInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Rule\RuleInterface;
use PHPUnit\Framework\TestCase;

class RateLimiterTest extends TestCase
{
    /**
     * Is consumed.
     *
     * Test that permission is consumed for identity.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\RateLimiter::addAlgorithm()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\RateLimiter::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\RateLimiter::consume()
     */
    public function testIsConsumed(): void
    {
        $permission = $this->createMock(PermissionInterface::class);
        $permission
            ->expects($this->once())
            ->method('implies')
            ->with($permission)
            ->willReturn(true);

        $rule = $this->createMock(RuleInterface::class);
        $rule
            ->expects($this->once())
            ->method('getPermission')
            ->willReturn($permission);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('getRules')
            ->with($identity)
            ->willReturn([
                $rule,
            ]);

        $quota = $this->createMock(QuotaInterface::class);

        $algorithm = $this->createMock(AlgorithmInterface::class);
        $algorithm
            ->expects($this->once())
            ->method('consume')
            ->with($rule, $identity)
            ->willReturn($quota);

        /**
         * @var RealmInterface      $realm
         * @var AlgorithmInterface  $algorithm
         * @var IdentityInterface   $identity
         * @var PermissionInterface $permission
         */
        $rateLimiter = new RateLimiter($algorithm);
        $rateLimiter->addRealm($realm);

        $this->assertSame($quota, $rateLimiter->consume($permission, $identity));
    }

    /**
     * Is not consumed.
     *
     * Test that permission is not consumed for identity.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\RateLimiter::addAlgorithm()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\RateLimiter::addRealm()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\RateLimiter::consume()
     */
    public function testIsNotConsumed(): void
    {
        $permission = $this->createMock(PermissionInterface::class);
        $permission
            ->expects($this->once())
            ->method('implies')
            ->with($permission)
            ->willReturn(false);

        $rule = $this->createMock(RuleInterface::class);
        $rule
            ->expects($this->once())
            ->method('getPermission')
            ->willReturn($permission);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('getRules')
            ->with($identity)
            ->willReturn([
                $rule,
            ]);

        $algorithm = $this->createMock(AlgorithmInterface::class);
        $algorithm
            ->expects($this->never())
            ->method('consume');

        /**
         * @var RealmInterface      $realm
         * @var AlgorithmInterface  $algorithm
         * @var IdentityInterface   $identity
         * @var PermissionInterface $permission
         */
        $rateLimiter = new RateLimiter($algorithm);
        $rateLimiter->addRealm($realm);

        $this->assertNull($rateLimiter->consume($permission, $identity));
    }
}
