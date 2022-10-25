<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Rule;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that correct values will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Rule\Rule::__construct()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Rule\Rule::getPermission()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Rule\Rule::getOptions()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Rule\Rule::getOption()
     */
    public function testGetIdentifier(): void
    {
        $permission = $this->createMock(PermissionInterface::class);

        /**
         * @var PermissionInterface $permission
         */
        $rule = new Rule($permission, ['limit' => 10]);

        $this->assertSame($permission, $rule->getPermission());
        $this->assertSame(['limit' => 10], $rule->getOptions());
        $this->assertSame(10, $rule->getOption('limit'));
        $this->assertSame($this, $rule->getOption('window', $this));
    }
}
