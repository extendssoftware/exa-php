<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity;

use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that correct values will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::getIdentifier()
     * @covers \ExtendsSoftware\ExaPHP\Identity\Identity::isAuthenticated()
     */
    public function testGetIdentifier(): void
    {
        $identity = new Identity('foo', true);

        $this->assertSame('foo', $identity->getIdentifier());
        $this->assertSame(true, $identity->isAuthenticated());
    }
}
