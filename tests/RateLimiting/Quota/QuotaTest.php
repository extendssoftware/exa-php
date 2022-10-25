<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Quota;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use PHPUnit\Framework\TestCase;

class QuotaTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that correct values will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Quota\Quota::__construct()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Quota\Quota::isConsumed()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Quota\Quota::getLimit()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Quota\Quota::getRemaining()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Quota\Quota::getReset()
     */
    public function testGetIdentifier(): void
    {
        /**
         * @var PermissionInterface $permission
         */
        $quota = new Quota(true, 10, 5, 1666697250);

        $this->assertTrue($quota->isConsumed());
        $this->assertSame(10, $quota->getLimit());
        $this->assertSame(5, $quota->getRemaining());
        $this->assertSame(1666697250, $quota->getReset());
    }
}
